<?php

namespace App\Models;

use CodeIgniter\Model;

class CesniModel extends Model
{
    protected $table            = 'cesni';        // Tablonun adı
    protected $primaryKey       = 'id';           // Anahtar sütun

    protected $allowedFields    = ['fis_no','agirlik','status_code','kullanilan'];    // Formdan gelebilecek alanlar

    protected $returnType       = 'array';        // Dilersen 'object' de yapabilirsin

    public $useTimestamps       = false;          // created_at / updated_at yoksa
}
