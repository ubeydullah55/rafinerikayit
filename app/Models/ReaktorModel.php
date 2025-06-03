<?php

namespace App\Models;

use CodeIgniter\Model;

class ReaktorModel extends Model
{
    protected $table            = 'reaktor';        // Tablonun adı
    protected $primaryKey       = 'id';             // Birincil anahtar
    protected $useAutoIncrement = true;             // Otomatik artan id
    
    protected $returnType       = 'array';          // Dönüş tipi: 'array' veya 'object'
    protected $useSoftDeletes   = false;            // Soft delete kullanılmayacaksa false

    protected $allowedFields    = ['fis_no', 'miktar']; // Kayıt eklerken/güncellerken kullanılabilir alanlar

    // Zaman damgaları (created_at, updated_at) kullanılıyor mu?
    protected $useTimestamps = false;
}
