<?php

namespace App\Models;

use CodeIgniter\Model;

/** Data access for the customers table specified in TA2. */
class CustomerModel extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['full_name', 'email', 'phone', 'created_at'];

    // TA2 has only created_at; it has no updated_at column.
    // The supplied sample SQL explicitly supplies each creation date.
    protected $useTimestamps = false;
}
