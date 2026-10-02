<?php

namespace App\Libraries;

use Config\Database;

/** Session cart with stock checked against the current database. */
class CartService
{
    private function quantities(): array
    {
        $stored = session()->get('cart');
        return is_array($stored) ? $stored : [];
    }

    public function count(): int
    {
        return array_sum(array_map('intval', $this->quantities()));
    }

    public function items(): array
    {
        $quantities = $this->quantities();
        $ids = array_values(array_filter(array_map('intval', array_keys($quantities)), static fn ($id) => $id > 0));
        if ($ids === []) {
            return [];
        }

        $rows = Database::connect()->table('product_variants AS v')
            ->select('v.id AS variant_id, v.label AS variant_label, v.stock_qty, v.is_active AS variant_active, p.name, p.price_cents, p.image_file, p.is_active AS product_active')
            ->join('products AS p', 'p.id = v.product_id')
            ->whereIn('v.id', $ids)
            ->get()->getResultArray();

        $items = [];
        foreach ($rows as $row) {
            $quantity = (int) ($quantities[$row['variant_id']] ?? 0);
            if ($quantity < 1 || (int) $row['variant_active'] !== 1 || (int) $row['product_active'] !== 1) {
                continue;
            }
            $row['quantity'] = $quantity;
            $row['line_total_cents'] = (int) $row['price_cents'] * $quantity;
            $items[] = $row;
        }
        return $items;
    }

    public function totalCents(array $items): int
    {
        return array_sum(array_column($items, 'line_total_cents'));
    }

    public function add(int $variantId, int $quantity): ?string
    {
        if ($variantId < 1 || $quantity < 1 || $quantity > 20) {
            return 'Choose a valid product option and quantity.';
        }
        $variant = Database::connect()->table('product_variants AS v')
            ->select('v.stock_qty, v.is_active AS variant_active, p.is_active AS product_active')
            ->join('products AS p', 'p.id = v.product_id')
            ->where('v.id', $variantId)->get()->getRowArray();
        if ($variant === null || (int) $variant['variant_active'] !== 1 || (int) $variant['product_active'] !== 1) {
            return 'That option is unavailable.';
        }
        $cart = $this->quantities();
        $newQuantity = (int) ($cart[$variantId] ?? 0) + $quantity;
        if ($newQuantity > (int) $variant['stock_qty']) {
            return 'There is not enough stock for that option.';
        }
        $cart[$variantId] = $newQuantity;
        session()->set('cart', $cart);
        return null;
    }

    public function update(int $variantId, int $quantity): ?string
    {
        $cart = $this->quantities();
        if (!isset($cart[$variantId])) {
            return 'That item is not in your cart.';
        }
        if ($quantity === 0) {
            unset($cart[$variantId]);
            session()->set('cart', $cart);
            return null;
        }
        if ($quantity < 0 || $quantity > 99) {
            return 'Choose a quantity from 0 to 99.';
        }
        $stock = Database::connect()->table('product_variants')
            ->select('stock_qty')->where('id', $variantId)->get()->getRowArray();
        if ($stock === null || $quantity > (int) $stock['stock_qty']) {
            return 'There is not enough stock for that option.';
        }
        $cart[$variantId] = $quantity;
        session()->set('cart', $cart);
        return null;
    }

    public function clear(): void
    {
        session()->remove('cart');
    }
}
