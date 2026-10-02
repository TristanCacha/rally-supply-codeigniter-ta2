<?php

namespace App\Controllers;

use App\Libraries\CartService;
use App\Models\CustomerModel;
use App\Models\SaleItemModel;
use App\Models\SaleModel;
use App\Models\UserModel;
use Config\Database;
use RuntimeException;
use Throwable;

class Sales extends BaseController
{
    public function checkout()
    {
        $cart = new CartService();
        $items = $cart->items();
        if ($items === []) {
            return redirect()->to(site_url('cart'));
        }
        foreach ($items as $item) {
            if ((int) $item['quantity'] > (int) $item['stock_qty']) {
                session()->setFlashdata('error', 'Stock changed. Update your cart before checkout.');
                return redirect()->to(site_url('cart'));
            }
        }
        return view('shop/checkout', [
            'title' => 'Complete sale',
            'items' => $items,
            'totalCents' => $cart->totalCents($items),
            'customers' => (new CustomerModel())->orderBy('full_name', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $cart = new CartService();
        $items = $cart->items();
        if ($items === []) {
            session()->setFlashdata('error', 'Your cart is empty.');
            return redirect()->to(site_url('cart'));
        }

        $submittedCustomer = $this->request->getPost('customer_id');
        $customerId = null;
        if ($submittedCustomer !== null && $submittedCustomer !== '') {
            if (!is_string($submittedCustomer) || !ctype_digit($submittedCustomer)
                || (new CustomerModel())->find((int) $submittedCustomer) === null) {
                session()->setFlashdata('error', 'Choose a valid customer account or walk-in sale.');
                return redirect()->to(site_url('pos/checkout'));
            }
            $customerId = (int) $submittedCustomer;
        }

        $db = Database::connect();
        $db->transBegin();
        try {
            foreach ($items as $item) {
                $quantity = (int) $item['quantity'];
                $db->table('product_variants')
                    ->set('stock_qty', 'stock_qty - ' . $quantity, false)
                    ->where('id', $item['variant_id'])
                    ->where('is_active', 1)
                    ->where('stock_qty >=', $quantity)
                    ->update();
                if ($db->affectedRows() !== 1) {
                    throw new RuntimeException('Stock changed for one of the selected options.');
                }
            }

            $db->table('sales')->insert([
                'customer_id' => $customerId,
                'user_id' => (int) session()->get('staff_id'),
                'total_cents' => $cart->totalCents($items),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $saleId = (int) $db->insertID();
            foreach ($items as $item) {
                $db->table('sale_items')->insert([
                    'sale_id' => $saleId,
                    'variant_id' => $item['variant_id'],
                    'product_name' => $item['name'],
                    'variant_label' => $item['variant_label'],
                    'unit_price_cents' => $item['price_cents'],
                    'quantity' => $item['quantity'],
                    'line_total_cents' => $item['line_total_cents'],
                ]);
            }
            if (!$db->transStatus()) {
                throw new RuntimeException('The sale could not be saved.');
            }
            $db->transCommit();
        } catch (Throwable $error) {
            $db->transRollback();
            log_message('error', 'Sale failed: {message}', ['message' => $error->getMessage()]);
            session()->setFlashdata('error', 'Sale could not be completed. Review the stock quantities and try again.');
            return redirect()->to(site_url('cart'));
        }

        $cart->clear();
        session()->setFlashdata('notice', 'Sale completed and stock updated.');
        return redirect()->to(site_url('sales/' . $saleId));
    }

    public function index(): string
    {
        $model = new SaleModel();
        $totalSales = $model->countAllResults();
        $sales = $model->orderBy('id', 'DESC')->findAll(20);
        $customers = array_column((new CustomerModel())->findAll(), 'full_name', 'id');
        $users = array_column((new UserModel())->findAll(), 'full_name', 'id');
        return view('staff/sales', [
            'title' => 'Recent sales', 'sales' => $sales,
            'totalSales' => $totalSales, 'customers' => $customers, 'users' => $users,
        ]);
    }

    public function receipt(int $id)
    {
        $sale = (new SaleModel())->find($id);
        if ($sale === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $customer = $sale['customer_id'] === null ? null : (new CustomerModel())->find($sale['customer_id']);
        $staff = (new UserModel())->find($sale['user_id']);
        return view('staff/receipt', [
            'title' => 'Sale #' . $id,
            'sale' => $sale,
            'items' => (new SaleItemModel())->where('sale_id', $id)->findAll(),
            'customer' => $customer,
            'staff' => $staff,
        ]);
    }
}
