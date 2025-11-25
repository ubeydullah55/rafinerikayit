<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CustomerModel;
use App\Models\CesniModel;
use App\Models\HurdaModel;
use App\Models\ReaktorModel;
use App\Models\TakozModel;

class CesniFireController extends BaseController
{
    public function __construct()
    {
        if (!session()->get('logged_in')) {
            header('Location: ' . base_url('/'));
            exit();
        }
    }



public function cesniFireTakipView()
{
    $cesniFireModel = new \App\Models\CesniFireModel();
    $cesniModel     = new \App\Models\CesniModel();
    $takozModel     = new \App\Models\TakozModel();

    // Fire + Takoz join, takoz.id = cesni_fire.takoz_kodu
    $fires = $cesniFireModel
        ->select('cesni_fire.*, takozlar.giris_gram, takozlar.musteri, takozlar.olculen_milyem,takozlar.created_date')
        ->join('takozlar', 'takozlar.id = cesni_fire.takoz_kodu', 'left')
        ->orderBy('cesni_fire.id', 'DESC') // ters sırala
        ->findAll();

    $data = [];

    foreach ($fires as $fire) {
        $takozKodu = $fire['takoz_kodu'];

        // Cesni + Takoz + Customer join
        $cesniler = $cesniModel
            ->select('
                cesni.*,
                takozlar.id as takoz_id,
                takozlar.giris_gram,
                takozlar.cesni_has,
                takozlar.olculen_milyem,
                takozlar.islem_goren_miktar,
                takozlar.status_code,
                takozlar.tur,
                customer.ad as musteri_adi
            ')
            ->join('takozlar', 'takozlar.id = cesni.fis_no', 'left')
            ->join('customer', 'customer.id = takozlar.musteri', 'left')
            ->where('cesni_takoz_kodu', $takozKodu)
            ->orderBy('cesni.id', 'DESC') // cesni kayıtlarını ters sırala
            ->findAll();

        $data[] = [
            'fire' => $fire,
            'cesniler' => $cesniler
        ];
    }

    return view('cesniFireTakipView', [
        'liste' => $data
    ]);
}



}
