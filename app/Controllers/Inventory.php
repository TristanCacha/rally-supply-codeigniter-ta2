<?php

namespace App\Controllers;

use App\Models\ProductVariantModel;
use Config\Database;

class Inventory extends BaseController
{
    public function index(): string
    {
        $variants = Database::connect()->table('product_variants AS v')
            ->select('v.id, v.sku, v.label, v.stock_qty, p.name, p.category_label, p.image_file')
            ->join('products AS p', 'p.id = v.product_id')
            ->orderBy('p.id', 'ASC')->orderBy('v.id', 'ASC')
            ->get()->getResultArray();
        return view('staff/inventory', ['title' => 'Inventory', 'variants' => $variants]);
    }

    public function update()
    {
        $id = $this->request->getPost('variant_id');
        $stock = $this->request->getPost('stock_qty');
        $variantId = is_string($id) && ctype_digit($id) ? (int) $id : 0;
        $quantity = is_string($stock) && ctype_digit($stock) ? (int) $stock : -1;
        if ($variantId < 1 || $quantity < 0 || $quantity > 9999) {
            session()->setFlashdata('error', 'Enter a whole stock quantity from 0 to 9999.');
            return redirect()->to(site_url('inventory'));
        }
        $model = new ProductVariantModel();
        if ($model->find($variantId) === null) {
            session()->setFlashdata('error', 'That product option was not found.');
            return redirect()->to(site_url('inventory'));
        }
        $model->update($variantId, ['stock_qty' => $quantity]);
        session()->setFlashdata('notice', 'Stock quantity saved.');
        return redirect()->to(site_url('inventory'));
    }
}
