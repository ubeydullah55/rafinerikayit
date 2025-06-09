<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customer'; // <-- Tablonun ismi
    protected $primaryKey = 'id'; // <-- Birincil anahtar

    protected $allowedFields = [
        'ad',
        'oran',
    ];

    protected $useTimestamps = false; // Eğer created_at, updated_at yoksa false
}
