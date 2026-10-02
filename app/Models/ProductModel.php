<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'sku', 'name', 'category_key', 'category_label', 'price_cents',
        'image_file', 'image_alt', 'description', 'badge', 'option_label',
        'specs_json', 'is_active',
    ];
}
