<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CustomerModel;
use App\Models\CesniModel;
use App\Models\HurdaModel;
use App\Models\ReaktorModel;
use App\Models\TakozModel;

class CesniTakozController extends BaseController
{
    public function __construct()
    {
        if (!session()->get('logged_in')) {
            header('Location: ' . base_url('/'));
            exit();
        }
    }

    public function cesniTakozView()
    {
        $model = new TakozModel();
        $modelcesni = new CesniModel();
        $hurdamodel = new HurdaModel();
        $db = \Config\Database::connect();



        // CESNİLER: status_code=1 olanlar + takozlar + customer join
        $cesniBuilder = $db->table('cesni');
        $cesniBuilder->select('cesni.*, takozlar.musteri,takozlar.gumus_milyem, takozlar.seri_no, takozlar.giris_gram, takozlar.tahmini_milyem, takozlar.olculen_milyem, takozlar.cesni_has,takozlar.tur, customer.ad as musteri_adi');
        $cesniBuilder->join('takozlar', 'cesni.fis_no = takozlar.id');
        $cesniBuilder->join('customer', 'customer.id = takozlar.musteri', 'left');
        $cesniBuilder->whereIn('cesni.status_code', [1, 2]);
        $cesnibilgi = $cesniBuilder->get()->getResultArray();



        // Toplam gramaj hesaplama (cesniler)
        $totalCesniGram = 0;
        $totalGercekHas = 0; // ✅ saflaştırılmış + dokunulmamışların has karşılığı toplamı
        $cesniAlinmamisHas = 0;

        foreach ($cesnibilgi as $item) {
            $agirlik = floatval($item['agirlik']);
            $kullanilan = floatval($item['kullanilan']);
            $cesniHas = floatval($item['cesni_has']);
            $tahminiMilyemAlinmayan = floatval($item['tahmini_milyem']);

            // mevcut toplam gram
            $totalCesniGram += $agirlik;

            // eğer saflaştırma yapılmışsa (kullanilan > 0)
            if ($kullanilan > 0 && $cesniHas > 0) {
                $milyem = $cesniHas / $kullanilan; // örnek: 0.916
                $milyem = $cesniHas / $kullanilan; // örnek: 0.916
                $dokunulmamis = $agirlik - $kullanilan; // kalan 2g
                $gercekHas = $cesniHas + ($dokunulmamis * $milyem);
                $cesnisiz=0;
            } else {
                // hiç saflaştırma yoksa has altın varsayımı = 0
                $gercekHas = 0;
                $cesnisiz=($agirlik *$tahminiMilyemAlinmayan)/1000;
            }

            $totalGercekHas += $gercekHas;
            $cesniAlinmamisHas+=$cesnisiz;
        }

        return view('cesniTakoz', [
            'role' => session()->get('role'),
            'cesnibilgi' => $cesnibilgi,
            'totalCesni' => $totalCesniGram,
            'totalGercekHas' => $totalGercekHas, // ✅ gerçek has toplamı gönderiliyor
            'cesniAlinmamisHas' => $cesniAlinmamisHas
        ]);
    }



    public function cesniTakozOlustur()
    {
        $request = service('request');
        $userAd = session()->get('name');

        $girisAgirlik = floatval($request->getPost('giris_agirlik'));
        $tahminiMilyem = floatval($request->getPost('tahmini_milyem'));
        $cesniIDs = json_decode($request->getPost('cesni_ids'), true);
        $beklenenHas = floatval($request->getPost('beklenen_has')); // 🔹 frontendden geliyor

        if ($girisAgirlik <= 0 || $tahminiMilyem <= 0) {
            return redirect()->back()->with('error', 'Geçersiz veri gönderildi.');
        }

        if (empty($cesniIDs) || !is_array($cesniIDs)) {
            return redirect()->back()->with('error', 'Herhangi bir çeşni seçilmedi.');
        }

        // 1️⃣ Takoz kaydını ekle
        $data = [
            'musteri'        => 2293,
            'giris_gram'     => $girisAgirlik,
            'tahmini_milyem' => $tahminiMilyem,
            'musteri_notu'   => '',
            'status_code'    => 1,
            'created_user'   => $userAd,
            'created_date'   => date('Y-m-d H:i:s'),
            'tur'    => 15, //ayarevinde çeşnitakoz olduğunu bulmak için
        ];

        $takozModel = new \App\Models\TakozModel();

        if ($takozModel->insert($data)) {
            // 2️⃣ Yeni eklenen takozun ID’sini al
            $takozID = $takozModel->getInsertID();

            // 3️⃣ Seçili cesnileri güncelle
            $cesniModel = new \App\Models\CesniModel();
            $cesniModel
                ->whereIn('id', $cesniIDs)
                ->set([
                    'status_code' => 2,
                    'cesni_takoz_kodu' => $takozID
                ])
                ->update();

            // 4️⃣ CesniFireModel'e kayıt ekle
            $cesniFireModel = new \App\Models\CesniFireModel();
            $fireData = [
                'takoz_kodu'  => $takozID,
                'beklenen_has' => $beklenenHas             
            ];
            $cesniFireModel->insert($fireData);

            return redirect()->back()->with('success', 'Takoz oluşturuldu, çeşniler ilişkilendirildi ve fire kaydı eklendi.');
        } else {
            return redirect()->back()->with('error', 'Kayıt eklenirken hata oluştu.');
        }
    }
}
