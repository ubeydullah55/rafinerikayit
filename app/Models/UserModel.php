<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users'; // tablo adın, istersen değiştir
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = true;

    protected $allowedFields    = [
        'name',
        'password',
        'role',
    ];

    protected $returnType       = 'array'; // veya 'object' da yapabilirsin
    protected $useSoftDeletes   = false;

    protected $validationRules = [
        'name'     => 'required|min_length[3]|max_length[255]',
        'password' => 'required|min_length[6]|max_length[255]',
        'role'     => 'required|integer|in_list[0,1,2,3]', // rol değerlerine göre düzenleyebilirsin
    ];

    protected $validationMessages = [
        'name' => [
            'required'    => 'İsim alanı zorunludur.',
            'min_length'  => 'İsim en az 3 karakter olmalıdır.',
            'max_length'  => 'İsim en fazla 255 karakter olabilir.',
        ],
        'password' => [
            'required'    => 'Şifre alanı zorunludur.',
            'min_length'  => 'Şifre en az 6 karakter olmalıdır.',
            'max_length'  => 'Şifre en fazla 255 karakter olabilir.',
        ],
        'role' => [
            'required' => 'Rol alanı zorunludur.',
            'integer'  => 'Rol bir tamsayı olmalıdır.',
            'in_list'  => 'Geçersiz rol değeri.',
        ],
    ];
}
