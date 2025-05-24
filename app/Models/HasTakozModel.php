<?php

namespace App\Models;

use CodeIgniter\Model;

class HasTakozModel extends Model
{
    protected $table = 'hastakoz'; // Tablonun adı
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'agirlik',
        'milyem',
        'grup_kodu',
        'islem_goren_miktar',
        'cesni_gram',
        'status_code'
    ];

    protected $returnType = 'array'; // Dilersen 'object' da yapabilirsin
    protected $useTimestamps = false; // created_at / updated_at yoksa false
}
