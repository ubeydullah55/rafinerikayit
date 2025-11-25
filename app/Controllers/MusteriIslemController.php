<?php

namespace App\Controllers;
use App\Models\UserModel;
use App\Models\CustomerModel;
class MusteriIslemController extends BaseController
{
    public function __construct()
    {
        if (!session()->get('logged_in')) {
            header('Location: ' . base_url('/'));
            exit();
        }
    }

    public function musteriView()
    {
  


        // Tüm müşterileri çek
    $customerModel = new \App\Models\CustomerModel();
    $musteriler = $customerModel->findAll();

        return view('musteriView', [
            'role' => session()->get('role'),
              'musteriler' => $musteriler,
        ]);
    }
    

public function musteriBilgiGetir()
{
    $musteri_id = $this->request->getPost('musteri_id'); // POST ile alıyoruz

    $customerModel = new \App\Models\CustomerModel();
    $musteriBilgi = $customerModel->find($musteri_id); // ID üzerinden bilgi çek

    // Takoz ve hurda verilerini çek
    $db = \Config\Database::connect();

    $takozlar = $db->table('takozlar')
        ->where('musteri', $musteri_id)
        ->get()->getResultArray();

    $hurdalar = $db->table('hurda')
        ->where('musteri', $musteri_id)
        ->get()->getResultArray();

    return view('musteriView', [
        'musteriBilgi' => $musteriBilgi,
        'takozlar' => $takozlar,
        'hurdalar' => $hurdalar,
        'musteriler' => $customerModel->findAll(),
    ]);
}

public function onaylaTakoz()
{
    $takoz_id = $this->request->getPost('takoz_id');

    if (!$takoz_id) {
        return $this->response->setJSON(['status' => 'error']);
    }

    $db = \Config\Database::connect();

    $db->table('takozlar')
        ->where('id', $takoz_id)
        ->update(['status_code' => 8]);

    return $this->response->setJSON(['status' => 'success']);
}


    
    
}
