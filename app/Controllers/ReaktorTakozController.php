<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CustomerModel;
use App\Models\CesniModel;
use App\Models\HurdaModel;
use App\Models\ReaktorModel;
use App\Models\TakozModel;

class ReaktorTakozController extends BaseController
{
    public function __construct()
    {
        if (!session()->get('logged_in')) {
            header('Location: ' . base_url('/'));
            exit();
        }
    }

    public function reaktorTakozView()
    {
        $reaktorModel = new \App\Models\ReaktorModel();
        $takozModel   = new \App\Models\TakozModel();

        // Reaktör kayıtlarını çek
        $reaktorlar = $reaktorModel
        ->where('reaktor_takoz_kodu', null)
            ->orderBy('fis_no', 'ASC')
            ->findAll();

        $data = [];
        foreach ($reaktorlar as $r) {
            $fisNo = $r['fis_no'];

            $takozlar = $takozModel
                ->select('takozlar.*, customer.ad AS musteri_adi')
                ->join('customer', 'customer.id = takozlar.musteri', 'left')
                ->where('grup_kodu', $fisNo)
                ->where('takozlar.tur', 0) //burası değişebilir
                ->findAll();

            $data[$fisNo] = [
                'reaktor'  => $r,
                'takozlar' => $takozlar
            ];
        }

        return view('reaktorTakoz', [
            'reaktorTakoz' => $data
        ]);
    }



    public function reaktorTakozOlustur2()
    {
        $request = service('request');
        $userAd = session()->get('name');

        // Frontend'den gelen veriler
        $takozGram = floatval($request->getPost('takoz_gram'));
        $toplamHas = floatval($request->getPost('toplam_has'));
        $toplamFarkliMadde = floatval($request->getPost('toplam_farkli'));
        $IDs = json_decode($request->getPost('secilenler'), true);

        // Validation
        if ($takozGram <= 0) {
            return redirect()->back()->with('error', 'Geçersiz takoz ağırlığı!');
        }

        if (empty($IDs) || !is_array($IDs)) {
            return redirect()->back()->with('error', 'Herhangi bir takoz seçilmedi!');
        }

        $takozModel = new \App\Models\TakozModel();
        $reaktorModel = new \App\Models\ReaktorModel();
        $reaktorFireModel = new \App\Models\ReaktorFireModel();

        // 1️⃣ Takoz kaydı ekle
        $data = [
            'musteri'        => 2294, // örnek sabit
            'giris_gram'     => $takozGram,
            'tahmini_milyem' => 0, // Opsiyonel: frontend’den gelirsen değiştir
            'musteri_notu'   => '',
            'status_code'    => 1,
            'created_user'   => $userAd,
            'created_date'   => date('Y-m-d H:i:s'),
            'tur'            => 25, // reaktor takoz
        ];

        if ($takozModel->insert($data)) {
            $takozID = $takozModel->getInsertID();

            // 2️⃣ Seçili cesnileri güncelle
            $reaktorModel
                ->whereIn('id', $IDs)
                ->set([
                    'reaktor_takoz_kodu' => $takozID
                ])
                ->update();

            // 3️⃣ CesniFireModel'e kayıt ekle (toplam beklenen has bilgisi)
            $fireData = [
                'takoz_kodu'  => $takozID,
                'beklenen_has' => $toplamHas,
                'farkli_madde' => $toplamFarkliMadde,
                'cikan_has' => 0
            ];
            $reaktorFireModel->insert($fireData);

            return redirect()->back()->with('success', 'Takoz oluşturuldu, çeşniler ilişkilendirildi ve fire kaydı eklendi.');
        } else {
            return redirect()->back()->with('error', 'Kayıt eklenirken hata oluştu.');
        }
    }

    public function reaktorTakozOlustur()
{
    $request = service('request');
    $userAd = session()->get('name');

    $takozGram = floatval($request->getPost('takoz_gram'));
    $toplamHas = floatval($request->getPost('toplam_has'));
    $toplamFarkliMadde = floatval($request->getPost('toplam_farkli'));
    $IDs = json_decode($request->getPost('secilenler'), true);

    if ($takozGram <= 0) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Geçersiz takoz ağırlığı!'
        ]);
    }

    if (empty($IDs) || !is_array($IDs)) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Herhangi bir reaktör seçilmedi!'
        ]);
    }

    $takozModel = new \App\Models\TakozModel();
    $reaktorModel = new \App\Models\ReaktorModel();
    $reaktorFireModel = new \App\Models\ReaktorFireModel();

    $data = [
        'musteri'        => 2294,
        'giris_gram'     => $takozGram,
        'tahmini_milyem' => 0,
        'musteri_notu'   => '',
        'status_code'    => 1,
        'created_user'   => $userAd,
        'created_date'   => date('Y-m-d H:i:s'),
        'tur'            => 25,
    ];

    if ($takozModel->insert($data)) {
        $takozID = $takozModel->getInsertID();

        $reaktorModel
            ->whereIn('id', $IDs)
            ->set(['reaktor_takoz_kodu' => $takozID])
            ->update();

        $fireData = [
            'takoz_kodu'  => $takozID,
            'beklenen_has' => $toplamHas,
            'farkli_madde' => $toplamFarkliMadde,
            'cikan_has' => 0,
            'created_date'   => date('Y-m-d H:i:s')
        ];
        $reaktorFireModel->insert($fireData);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Takoz oluşturuldu, reaktörler ilişkilendirildi!'
        ]);
    }

    return $this->response->setJSON([
        'status' => 'error',
        'message' => 'Kayıt eklenirken hata oluştu.'
    ]);
}

}
