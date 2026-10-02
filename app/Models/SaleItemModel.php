<?php

namespace App\Models;

use CodeIgniter\Model;

class SaleItemModel extends Model
{
    protected $table = 'sale_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'sale_id', 'variant_id', 'product_name', 'variant_label',
        'unit_price_cents', 'quantity', 'line_total_cents',
    ];
}
