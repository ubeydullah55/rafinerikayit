<?php

namespace App\Models;

use CodeIgniter\Model;

class ReaktorFireModel extends Model
{
    protected $table            = 'reaktor_fire';        // Tablonun adı
    protected $primaryKey       = 'id';             // Birincil anahtar
    protected $useAutoIncrement = true;             // Otomatik artan id
    
    protected $returnType       = 'array';          // Dönüş tipi: 'array' veya 'object'
    protected $useSoftDeletes   = false;            // Soft delete kullanılmayacaksa false

    protected $allowedFields    = ['takoz_kodu', 'beklenen_has','farkli_madde','cikan_has','created_date']; // Kayıt eklerken/güncellerken kullanılabilir alanlar

    // Zaman damgaları (created_at, updated_at) kullanılıyor mu?
    protected $useTimestamps = false;
}
