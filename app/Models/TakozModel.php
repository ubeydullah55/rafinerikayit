<?php

namespace App\Models;

use CodeIgniter\Model;

class TakozModel extends Model
{
    protected $table = 'takozlar'; // Şimdilik dummy olabilir
    protected $primaryKey = 'id';

    protected $allowedFields = ['musteri', 'giris_gram', 'tahmini_milyem', 'musteri_notu', 'status_code', 'islem_goren_miktar', 'cesni_gram', 'olculen_milyem', 'cesni_has', 'grup_kodu', 'hurda_grup_kodu', 'cesni_id', 'tur'];

    // İstersen timestamp da açabilirsin
    // protected $useTimestamps = true;
    public function getWithCustomer()
    {
        return $this->select('takozlar.*, customers.name as musteri_adi')
            ->join('customers', 'customers.id = takozlar.musteri')
            ->findAll();
    }
}
