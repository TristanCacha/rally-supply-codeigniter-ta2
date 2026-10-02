<?php

namespace App\Models;

use CodeIgniter\Model;

class WebOrderItemModel extends Model
{
    protected $table = 'web_order_items';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'order_id', 'variant_id', 'product_name', 'variant_label',
        'unit_price_cents', 'quantity', 'line_total_cents',
    ];
}
