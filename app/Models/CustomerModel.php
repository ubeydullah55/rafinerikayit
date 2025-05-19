<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customer'; // <-- Tablonun ismi
    protected $primaryKey = 'id'; // <-- Birincil anahtar

    protected $allowedFields = [
        'ad',
        'soyad',
        'tc',
        'dogum_tarihi',
        'dogum_yeri',
        'anne_adi',
        'baba_adi',
        'uyruk',
        'kimlik_belgesi_turu',
        'kimlik_belgesi_numarası',
        'e_posta',
        'tel',
        'meslek',
        'sehir',
        'adres',
        'musteri_notu',
        'img_1',
        'img_2',
        'created_date',
        'ekleyen_id',
        'status',
    ];

    protected $useTimestamps = false; // Eğer created_at, updated_at yoksa false
}
