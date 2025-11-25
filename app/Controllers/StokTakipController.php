<?php

namespace App\Controllers;

use App\Models\HurdaModel;
use App\Models\TakozModel;
use App\Models\CustomerModel;

class StokTakipController extends BaseController
{
    public function __construct()
    {
        if (!session()->get('logged_in')) {
            header('Location: ' . base_url('/'));
            exit();
        }
    }
    /*
     public function index()
    {    
        $hurdaModel = new HurdaModel();
        $takozModel = new TakozModel();

        // 1️⃣ Mal Kabul -> status_code = 1
        $malKabulHurda = $hurdaModel->where('status_code', 1)->findAll();
        $malKabulTakoz = $takozModel->where('status_code', 1)->findAll();

        // 2️⃣ İfraz -> status_code = 2
        $ifrazTakoz = $takozModel->where('status_code', 2)->findAll();

        // 3️⃣ Ayar Evi -> status_code = 3
        $ayarEviTakoz = $takozModel->where('status_code', 3)->findAll();

        return view('stokTakipSecim', [
            'malKabulHurda' => $malKabulHurda,
            'malKabulTakoz' => $malKabulTakoz,
            'ifrazTakoz'    => $ifrazTakoz,
            'ayarEviTakoz'  => $ayarEviTakoz
        ]);
    }
*/

    public function index()
    {
        $db = \Config\Database::connect();
        $hurdaModel = new \App\Models\HurdaModel();
        $takozModel = new \App\Models\TakozModel();
        $customerModel = new \App\Models\CustomerModel();

        // 1. Müşteri tablosundaki tüm kayıtları çek
        $customers = $customerModel->findAll();

        // 2. Müşterileri id -> ad şeklinde map'le
        $customerMap = [];
        foreach ($customers as $c) {
            $customerMap[$c['id']] = $c['ad'] ?? 'Bilinmiyor';
        }

        #region malkabul

        // 3. Hurda ve Takoz listelerini çek
        $malKabulHurda = $hurdaModel
            ->where('status_code', 1)
            ->orderBy('id', 'DESC')
            ->findAll();

        $malKabulTakoz = $takozModel
            ->where('status_code', 1)
            ->orderBy('id', 'DESC')
            ->findAll();

        $totalMalKabul = 0;

        // Hurda gramlarını topla
        foreach ($malKabulHurda as $h) {
            $totalMalKabul += $h['giris_gram'] ?? 0;
        }

        // Takoz gramlarını topla
        foreach ($malKabulTakoz as $t) {
            $totalMalKabul += $t['giris_gram'] ?? 0;
        }

        #endregion


        #region ifraz

        // Eğer ilerde ifraz veya ayarevi gibi türler eklenecekse:
        $ifrazTakoz = $takozModel
            ->whereIn('status_code', [2, 3, 5, 7])
            ->orderBy('id', 'DESC')
            ->findAll();

        $ifrazHurda = $hurdaModel
            ->where('status_code', 2)
            ->orderBy('id', 'DESC')
            ->findAll();
        #endregion


        // 4. Her listeye müşteri adını ekle
        $lists = [
            'malKabulHurda' => $malKabulHurda,
            'malKabulTakoz' => $malKabulTakoz,
            'ifrazTakoz'    => $ifrazTakoz,
            'ifrazHurda'    => $ifrazHurda,
        ];

        foreach ($lists as $key => $list) {
            foreach ($list as $i => $row) {
                $mId = $row['musteri'] ?? null;
                $lists[$key][$i]['musteri_adi'] = ($mId && isset($customerMap[$mId])) ? $customerMap[$mId] : 'Bilinmiyor';
            }
        }




        // CESNİLER: status_code=1 olanlar + takozlar + customer join
        $cesniBuilder = $db->table('cesni');
        $cesniBuilder->select('cesni.*, takozlar.musteri,takozlar.gumus_milyem, takozlar.giris_gram, takozlar.tahmini_milyem, takozlar.olculen_milyem, takozlar.cesni_has, customer.ad as musteri_adi');
        $cesniBuilder->join('takozlar', 'cesni.fis_no = takozlar.id');
        $cesniBuilder->join('customer', 'customer.id = takozlar.musteri', 'left');
        $cesniBuilder->where('cesni.status_code', 1);
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
                $dokunulmamis = $agirlik - $kullanilan; // kalan 2g
                $gercekHas = $cesniHas + ($dokunulmamis * $milyem);
                $cesnisiz = 0;
            } else {
                // hiç saflaştırma yoksa has altın varsayımı = 0
                $gercekHas = 0;
                $cesnisiz = ($agirlik * $tahminiMilyemAlinmayan) / 1000;
            }

            $totalGercekHas += $gercekHas;
            $cesniAlinmamisHas += $cesnisiz;
        }

        $reaktorModel = new \App\Models\ReaktorModel();
        $takozModel   = new \App\Models\TakozModel();

        // Reaktör kayıtlarını çek
        $reaktorlar = $reaktorModel
            ->where('reaktor_takoz_kodu', null)
            ->orderBy('fis_no', 'ASC')
            ->findAll();

        $data = [];
        $reaktortoplamHasMiktar       = 0;
        $toplamKarisikFire  = 0;
        $toplamFarkliMadde  = 0;
        foreach ($reaktorlar as $r) {
            $reaktortoplamHasMiktar      += floatval($r['miktar']);
            $toplamKarisikFire += floatval($r['karisik_fire']);
            $toplamFarkliMadde += floatval($r['farkli_madde']);
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

        // 5. View’a gönder
        return view('stokTakipSecim', [
            'malKabulHurda' => $lists['malKabulHurda'],
            'malKabulTakoz' => $lists['malKabulTakoz'],
            'totalMalKabul' => $totalMalKabul,
            'ifrazTakoz'    => $lists['ifrazTakoz'],
            'ifrazHurda'    => $lists['ifrazHurda'],
            'cesnibilgi' => $cesnibilgi,
            'totalCesni' => $totalCesniGram,
            'totalGercekHas' => $totalGercekHas,
            'cesniAlinmamisHas' => $cesniAlinmamisHas,
            'reaktorTakoz' => $data,
            'reaktortoplamHasMiktar'       => $reaktortoplamHasMiktar,
            'toplamKarisikFire'  => $toplamKarisikFire,
            'toplamFarkliMadde'  => $toplamFarkliMadde
        ]);
    }
}
