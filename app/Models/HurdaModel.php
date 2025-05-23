<?php

namespace App\Models;

use CodeIgniter\Model;

class HurdaModel extends Model
{
    protected $table = 'hurda'; // Şimdilik dummy olabilir
    protected $primaryKey = 'id';

    protected $allowedFields = [ 'musteri', 'giris_gram', 'tahmini_milyem', 'musteri_notu','status_code'];

    // İstersen timestamp da açabilirsin
    // protected $useTimestamps = true;
}
