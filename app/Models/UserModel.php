<?php

namespace App\Models;

use CodeIgniter\Model;

/** Data access for the users table specified in TA2. */
class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['username', 'full_name', 'created_at'];

    // TA2 has only created_at; it has no updated_at column.
    // The supplied sample SQL explicitly supplies each creation date.
    protected $useTimestamps = false;
}
