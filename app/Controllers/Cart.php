<?php

namespace App\Controllers;

use App\Libraries\CartService;

class Cart extends BaseController
{
    public function index(): string
    {
        $cart = new CartService();
        $items = $cart->items();
        $stockIssue = false;
        foreach ($items as $item) {
            if ((int) $item['quantity'] > (int) $item['stock_qty']) {
                $stockIssue = true;
                break;
            }
        }
        return view('shop/cart', [
            'title' => 'Your cart',
            'items' => $items,
            'totalCents' => $cart->totalCents($items),
            'stockIssue' => $stockIssue,
        ]);
    }

    public function add()
    {
        $variant = $this->request->getPost('variant_id');
        $quantity = $this->request->getPost('quantity');
        $variantId = is_string($variant) && ctype_digit($variant) ? (int) $variant : 0;
        $amount = is_string($quantity) && ctype_digit($quantity) ? (int) $quantity : 0;
        $error = (new CartService())->add($variantId, $amount);
        session()->setFlashdata($error === null ? 'notice' : 'error', $error ?? 'Added to your cart.');
        return redirect()->to($error === null ? site_url('cart') : site_url('shop'));
    }

    public function update()
    {
        $variant = $this->request->getPost('variant_id');
        $quantity = $this->request->getPost('quantity');
        $variantId = is_string($variant) && ctype_digit($variant) ? (int) $variant : 0;
        $amount = is_string($quantity) && ctype_digit($quantity) ? (int) $quantity : -1;
        $error = (new CartService())->update($variantId, $amount);
        session()->setFlashdata($error === null ? 'notice' : 'error', $error ?? 'Cart updated.');
        return redirect()->to(site_url('cart'));
    }

    public function remove()
    {
        $variant = $this->request->getPost('variant_id');
        $variantId = is_string($variant) && ctype_digit($variant) ? (int) $variant : 0;
        $error = (new CartService())->update($variantId, 0);
        session()->setFlashdata($error === null ? 'notice' : 'error', $error ?? 'Item removed.');
        return redirect()->to(site_url('cart'));
    }
}
