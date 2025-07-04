<?php

namespace App\Models;

use CodeIgniter\Model;

class TakozModel extends Model
{
    protected $table = 'takozlar'; // Şimdilik dummy olabilir
    protected $primaryKey = 'id';

    protected $allowedFields = ['musteri', 'giris_gram', 'tahmini_milyem', 'musteri_notu', 'status_code', 'islem_goren_miktar', 'cesni_gram', 'olculen_milyem', 'cesni_has', 'grup_kodu', 'hurda_grup_kodu', 'cesni_id', 'tur','created_date','created_user'];

    // İstersen timestamp da açabilirsin
    // protected $useTimestamps = true;

}
