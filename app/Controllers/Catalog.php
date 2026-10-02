<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ProductVariantModel;

class Catalog extends BaseController
{
    public function index(): string
    {
        $filters = [
            'all' => 'All gear',
            'paddles' => 'Paddles',
            'balls' => 'Balls',
            'bags' => 'Bags',
            'grips' => 'Grips',
            'protection' => 'Edge tape',
            'footwear' => 'Court shoes',
            'apparel' => 'Apparel',
            'accessories' => 'Accessories',
        ];
        $requestedFilter = $this->request->getGet('category');
        $activeFilter = is_string($requestedFilter) && isset($filters[$requestedFilter])
            ? $requestedFilter
            : 'all';

        $model = new ProductModel();
        $model->where('is_active', 1);
        if ($activeFilter !== 'all') {
            $model->where('category_key', $activeFilter);
        }
        $products = $model->orderBy('id', 'ASC')->findAll();

        $variantsByProduct = [];
        if ($products !== []) {
            $variants = (new ProductVariantModel())
                ->whereIn('product_id', array_column($products, 'id'))
                ->where('is_active', 1)
                ->orderBy('id', 'ASC')->findAll();
            foreach ($variants as $variant) {
                $variantsByProduct[$variant['product_id']][] = $variant;
            }
        }
        foreach ($products as &$product) {
            $product['variants'] = $variantsByProduct[$product['id']] ?? [];
            $product['specs'] = json_decode($product['specs_json'], true) ?: [];
        }
        unset($product);

        return view('pages/shop', [
            'title' => 'Pickleball gear and accessories',
            'filters' => $filters,
            'activeFilter' => $activeFilter,
            'products' => $products,
        ]);
    }
}
