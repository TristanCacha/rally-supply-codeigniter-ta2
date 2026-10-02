<?php

namespace App\Models;

use CodeIgniter\Model;

class StaffCredentialModel extends Model
{
    protected $table = 'staff_credentials';
    protected $primaryKey = 'user_id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'password_hash', 'is_active', 'created_at'];
}
