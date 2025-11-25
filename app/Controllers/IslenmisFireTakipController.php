<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CustomerModel;
use App\Models\CesniModel;
use App\Models\HurdaModel;
use App\Models\ReaktorModel;
use App\Models\TakozModel;

class IslenmisFireTakipController extends BaseController
{
    public function __construct()
    {
        if (!session()->get('logged_in')) {
            header('Location: ' . base_url('/'));
            exit();
        }
    }




    public function islenmisFireTakipView()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        $db = \Config\Database::connect();

        // ÜST GRUPLAR (islenmis_fire tablosu)
        $ustGruplar = $db->table('islenmis_fire')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        $gruplar = [];

        foreach ($ustGruplar as $ust) {
            $ustGrupId = (int)$ust['grup_kodu'];

            // STATUS 7 TAKOZLAR
            $altKayitlar = $db->table('takozlar')
                ->select('takozlar.*, customer.ad AS musteri_adi')
                ->join('customer', 'customer.id = takozlar.musteri', 'left')
                ->where('takozlar.islenmis_grup_kodu', $ustGrupId)
                ->whereIn('takozlar.status_code', [6, 7, 8])
                ->orderBy('takozlar.id', 'ASC')
                ->get()
                ->getResultArray();


            $gruplar[$ustGrupId] = [
                'ust' => $ust,
                'alt' => $altKayitlar,        // status 7
            ];
        }


        return view('islenmisFireTakipView', [
            'role'    => session()->get('role'),
            'gruplar' => $gruplar
        ]);
    }
}
