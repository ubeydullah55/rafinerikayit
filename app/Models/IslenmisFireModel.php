<?php

namespace App\Models;

use CodeIgniter\Model;

class IslenmisFireModel extends Model
{
    protected $table            = 'islenmis_fire';        // Tablonun adı
    protected $primaryKey       = 'id';             // Birincil anahtar
    protected $useAutoIncrement = true;             // Otomatik artan id
    
    protected $returnType       = 'array';          // Dönüş tipi: 'array' veya 'object'
    protected $useSoftDeletes   = false;            // Soft delete kullanılmayacaksa false

    protected $allowedFields    = ['grup_kodu', 'miktar','created_date']; // Kayıt eklerken/güncellerken kullanılabilir alanlar

    // Zaman damgaları (created_at, updated_at) kullanılıyor mu?
    protected $useTimestamps = false;
}
