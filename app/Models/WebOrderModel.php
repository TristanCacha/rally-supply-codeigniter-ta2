<?php

namespace App\Models;

use CodeIgniter\Model;

class WebOrderModel extends Model
{
    protected $table = 'web_orders';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'public_token', 'buyer_name', 'buyer_email', 'buyer_phone',
        'status', 'total_cents', 'created_at', 'updated_at',
    ];
}
