<?php

namespace App\Controllers;

use App\Libraries\CartService;
use App\Models\WebOrderItemModel;
use App\Models\WebOrderModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Database;
use RuntimeException;
use Throwable;

class Orders extends BaseController
{
    public function checkout()
    {
        $cart = new CartService();
        $items = $cart->items();
        if ($items === []) {
            return redirect()->to(site_url('cart'));
        }
        if ($cart->count() !== array_sum(array_column($items, 'quantity'))) {
            session()->setFlashdata('error', 'An item is no longer available. Review your cart.');
            return redirect()->to(site_url('cart'));
        }
        foreach ($items as $item) {
            if ((int) $item['quantity'] > (int) $item['stock_qty']) {
                session()->setFlashdata('error', 'Stock changed. Update your cart before checkout.');
                return redirect()->to(site_url('cart'));
            }
        }
        return view('shop/web_checkout', [
            'title' => 'Checkout',
            'items' => $items,
            'totalCents' => $cart->totalCents($items),
            'input' => session()->getFlashdata('checkout_input') ?: [],
        ]);
    }

    public function store()
    {
        $cart = new CartService();
        $items = $cart->items();
        if ($items === [] || $cart->count() !== array_sum(array_column($items, 'quantity'))) {
            session()->setFlashdata('error', 'Review your cart before placing an order.');
            return redirect()->to(site_url('cart'));
        }

        $name = $this->request->getPost('buyer_name');
        $email = $this->request->getPost('buyer_email');
        $phone = $this->request->getPost('buyer_phone');
        $name = is_string($name) ? trim($name) : '';
        $email = is_string($email) ? trim($email) : '';
        $phone = is_string($phone) ? trim($phone) : '';
        $valid = mb_strlen($name) >= 2 && mb_strlen($name) <= 100
            && mb_strlen($email) <= 100 && filter_var($email, FILTER_VALIDATE_EMAIL) !== false
            && ($phone === '' || (strlen($phone) <= 20 && preg_match('/^[0-9+(). -]{7,20}$/', $phone) === 1));
        if (!$valid) {
            session()->setFlashdata('error', 'Enter your name and a valid email. Check the optional phone number.');
            session()->setFlashdata('checkout_input', ['name' => $name, 'email' => $email, 'phone' => $phone]);
            return redirect()->to(site_url('checkout'));
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
                    throw new RuntimeException('Stock changed for a selected option.');
                }
            }

            $token = bin2hex(random_bytes(24));
            $now = date('Y-m-d H:i:s');
            $db->table('web_orders')->insert([
                'public_token' => $token,
                'buyer_name' => $name,
                'buyer_email' => $email,
                'buyer_phone' => $phone === '' ? null : $phone,
                'status' => 'pending',
                'total_cents' => $cart->totalCents($items),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $orderId = (int) $db->insertID();
            foreach ($items as $item) {
                $db->table('web_order_items')->insert([
                    'order_id' => $orderId,
                    'variant_id' => $item['variant_id'],
                    'product_name' => $item['name'],
                    'variant_label' => $item['variant_label'],
                    'unit_price_cents' => $item['price_cents'],
                    'quantity' => $item['quantity'],
                    'line_total_cents' => $item['line_total_cents'],
                ]);
            }
            if (!$db->transStatus()) {
                throw new RuntimeException('The order could not be saved.');
            }
            $db->transCommit();
        } catch (Throwable $error) {
            $db->transRollback();
            log_message('error', 'Web order failed: {message}', ['message' => $error->getMessage()]);
            session()->setFlashdata('error', 'Your order was not placed. Review stock in your cart and try again.');
            return redirect()->to(site_url('cart'));
        }

        $cart->clear();
        return redirect()->to(site_url('order/' . $token));
    }

    public function confirmation(string $token)
    {
        if (preg_match('/^[a-f0-9]{48}$/', $token) !== 1) {
            throw PageNotFoundException::forPageNotFound();
        }
        $order = (new WebOrderModel())->where('public_token', $token)->first();
        if ($order === null) {
            throw PageNotFoundException::forPageNotFound();
        }
        $this->response->setHeader('Cache-Control', 'private, no-store');
        $this->response->setHeader('Referrer-Policy', 'no-referrer');
        return view('shop/order_confirmation', [
            'title' => 'Order #' . $order['id'],
            'order' => $order,
            'items' => (new WebOrderItemModel())->where('order_id', $order['id'])->findAll(),
        ]);
    }

    public function index(): string
    {
        $model = new WebOrderModel();
        return view('staff/orders', [
            'title' => 'Website orders',
            'totalOrders' => $model->countAllResults(),
            'orders' => $model->orderBy('id', 'DESC')->findAll(50),
        ]);
    }

    public function updateStatus(int $id)
    {
        $status = $this->request->getPost('status');
        if (!is_string($status) || !in_array($status, ['fulfilled', 'cancelled'], true)) {
            session()->setFlashdata('error', 'Choose a valid order action.');
            return redirect()->to(site_url('orders'));
        }

        $db = Database::connect();
        $db->transBegin();
        try {
            $order = $db->query('SELECT status FROM web_orders WHERE id = ? FOR UPDATE', [$id])->getRowArray();
            if ($order === null || $order['status'] !== 'pending') {
                throw new RuntimeException('Only pending orders can be updated.');
            }
            if ($status === 'cancelled') {
                $items = $db->table('web_order_items')->where('order_id', $id)->get()->getResultArray();
                foreach ($items as $item) {
                    $db->table('product_variants')
                        ->set('stock_qty', 'stock_qty + ' . (int) $item['quantity'], false)
                        ->where('id', $item['variant_id'])->update();
                    if ($db->affectedRows() !== 1) {
                        throw new RuntimeException('Could not release reserved stock.');
                    }
                }
            }
            $db->table('web_orders')->where('id', $id)->where('status', 'pending')->update([
                'status' => $status,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            if ($db->affectedRows() !== 1 || !$db->transStatus()) {
                throw new RuntimeException('Could not update the order.');
            }
            $db->transCommit();
            session()->setFlashdata('notice', 'Order #' . $id . ' marked ' . $status . '.');
        } catch (Throwable $error) {
            $db->transRollback();
            log_message('error', 'Order status failed: {message}', ['message' => $error->getMessage()]);
            session()->setFlashdata('error', 'Order could not be updated. Refresh the list and try again.');
        }
        return redirect()->to(site_url('orders'));
    }
}
