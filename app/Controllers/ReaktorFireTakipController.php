<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\CustomerModel;
use App\Models\CesniModel;
use App\Models\HurdaModel;
use App\Models\ReaktorModel;
use App\Models\TakozModel;

class ReaktorFireTakipController extends BaseController
{
    public function __construct()
    {
        if (!session()->get('logged_in')) {
            header('Location: ' . base_url('/'));
            exit();
        }
    }

    public function reaktorFireTakipView()
    {
        $db = \Config\Database::connect();

        // Reaktor-Fire + Reaktor join
        $builder = $db->table('reaktor_fire AS rf');
        $builder->select('rf.id AS fire_id, rf.takoz_kodu, rf.beklenen_has, rf.farkli_madde, rf.cikan_has,
                      r.id AS reaktor_id, r.fis_no, r.miktar, r.karisik_fire, r.farkli_madde AS reaktor_farkli_madde,
                      r.aciklama, r.created_date, r.created_user');
        $builder->join('reaktor AS r', 'rf.takoz_kodu = r.reaktor_takoz_kodu', 'inner');
        $builder->orderBy('rf.id', 'ASC');
        $reaktorlar = $builder->get()->getResultArray();

        $takozModel = new \App\Models\TakozModel();
        $data = [];

        foreach ($reaktorlar as $r) {
            $fireId = $r['fire_id'];

            // Eğer fire_id daha önce eklenmemişse ekle
            if (!isset($data[$fireId])) {
                $data[$fireId] = [
                    'reaktor_fire' => $r,
                    'reaktorlar'   => []
                ];
            }

            // Her reaktörün takozları
            $takozlar = $takozModel
                ->select('takozlar.*, customer.ad AS musteri_adi')
                ->join('customer', 'customer.id = takozlar.musteri', 'left')
                ->where('grup_kodu', $r['fis_no'])
                ->where('takozlar.tur', 0)
                ->findAll();

            $data[$fireId]['reaktorlar'][] = [
                'id' => $r['reaktor_id'],
                'fis_no' => $r['fis_no'],
                'miktar' => $r['miktar'],
                'karisik_fire' => $r['karisik_fire'],
                'farkli_madde' => $r['reaktor_farkli_madde'],
                'aciklama' => $r['aciklama'],
                'created_date' => $r['created_date'],
                'created_user' => $r['created_user'],
                'takozlar' => $takozlar  // 2. tablo için
            ];
        }

        return view('reaktorFireTakipView', [
            'reaktorTakoz' => $data
        ]);
    }



    public function reaktorFireTakipView2()
    {
        $db = \Config\Database::connect();

        // TAKOZLAR
        $builder = $db->table('takozlar');
        $builder->select('takozlar.*, customer.ad as musteri_adi, customer.oran');
        $builder->join('customer', 'customer.id = takozlar.musteri', 'left');
        $builder->whereIn('takozlar.status_code', [1, 2, 3, 4,5,6]); //5 ve 6 hastakoz getirmek için
        $builder->whereIn('takozlar.tur', [0,2,3, 15, 25]); //2 ve 3 hastakoz getirmek için 
        $builder->orderBy('takozlar.id', 'DESC');
        $items = $builder->get()->getResultArray();

        // HURDALAR
        $hurdaBuilder = $db->table('hurda');
        $hurdaBuilder->select('hurda.*, customer.ad as musteri_adi');
        $hurdaBuilder->join('customer', 'customer.id = hurda.musteri', 'left');
        $hurdaBuilder->where('hurda.status_code', 1);
        $hurdaBuilder->orderBy('hurda.id', 'DESC');
        $hurdalar = $hurdaBuilder->get()->getResultArray();

        // GRUPLA
        $gruplar = [];
        $tekilKayitlar = [];
        $grupKodlari = []; // reaktor için lazım

        foreach ($items as $item) {
            $grupKodu = (int) ($item['grup_kodu'] ?? 0);

            if ($grupKodu > 0) {
                $gruplar[$grupKodu][] = $item;
                $grupKodlari[] = $grupKodu;
            } else {
                $tekilKayitlar[] = $item;
            }
        }

        // grup kodlarını benzersiz yap
        $grupKodlari = array_unique($grupKodlari);

        // REAKTOR TABLOSU BİLGİLERİ (fis_no ile grup_kodu eşleşiyor)
        $reaktorBilgileri = [];
        if (!empty($grupKodlari)) {
            $reaktorQuery = $db->table('reaktor');
            $reaktorQuery->whereIn('fis_no', $grupKodlari);
            $result = $reaktorQuery->get()->getResultArray();

            foreach ($result as $row) {
                $reaktorBilgileri[$row['fis_no']] = $row;
            }
        }

        // 🔥 REAKTOR_FIRE TABLOSU BİLGİLERİ
        $rfBuilder = $db->table('reaktor_fire');
        $rfBuilder->select('*');
        $reaktorFire = $rfBuilder->get()->getResultArray();

        // takoz_kod → reaktor_fire bilgisi
        $reaktorFireMap = [];
        foreach ($reaktorFire as $rf) {
            $reaktorFireMap[$rf['takoz_kodu']] = $rf;
        }

        // 🔥 ÜST GRUPLAMA: reaktor_takoz_kodu -> grup_kodu -> items
        $ustGruplar = [];
        foreach ($gruplar as $grupKodu => $itemsGroup) {

            // reaktor tablosundan reaktor_takoz_kodu al
            $reaktorKod = 0;
            if (isset($reaktorBilgileri[$grupKodu])) {
                $reaktorKod = $reaktorBilgileri[$grupKodu]['reaktor_takoz_kodu'] ?? 0;
            }

            // üst grup map'ine ekle
            $ustGruplar[$reaktorKod][$grupKodu] = $itemsGroup;
        }

        // TOPLAM GRAMLAR
        $totalGram = array_sum(array_column($items, 'giris_gram'));
        $hurdatotalGram = array_sum(array_column($hurdalar, 'giris_gram'));

        return view('reaktorFireTakipView2', [
            'ustGruplar' => $ustGruplar,           // üst gruplar
            'reaktorFireMap' => $reaktorFireMap,   // reaktor_fire bilgileri
            'gruplar' => $gruplar,                 // mevcut grup kodları
            'tekilKayitlar' => $tekilKayitlar,
            'reaktorBilgileri' => $reaktorBilgileri,
            'totalGram' => $totalGram,
            'hurdalar' => $hurdalar,
            'hurdatotalGram' => $hurdatotalGram,
            'role' => session()->get('role'),
        ]);
    }




    public function reaktorFireTakipView3()
    {
        $db = \Config\Database::connect();

        // Reaktor-Fire + Reaktor join
        $builder = $db->table('reaktor_fire AS rf');
        $builder->select('rf.id AS fire_id, rf.takoz_kodu, rf.beklenen_has, rf.farkli_madde, rf.cikan_has,
                      r.id AS reaktor_id, r.fis_no, r.miktar, r.karisik_fire, r.farkli_madde AS reaktor_farkli_madde,
                      r.aciklama, r.created_date, r.created_user');
        $builder->join('reaktor AS r', 'rf.takoz_kodu = r.reaktor_takoz_kodu', 'inner');
        $builder->orderBy('rf.id', 'ASC');
        $reaktorlar = $builder->get()->getResultArray();

        $takozModel = new \App\Models\TakozModel();
        $data = [];

        foreach ($reaktorlar as $r) {
            $fireId = $r['fire_id'];

            // Eğer fire_id daha önce eklenmemişse ekle
            if (!isset($data[$fireId])) {
                $data[$fireId] = [
                    'reaktor_fire' => $r,
                    'reaktorlar'   => []
                ];
            }

            // Her reaktörün takozları
            $takozlar = $takozModel
                ->select('takozlar.*, customer.ad AS musteri_adi')
                ->join('customer', 'customer.id = takozlar.musteri', 'left')
                ->where('grup_kodu', $r['fis_no'])
                ->where('takozlar.tur', 0)
                ->findAll();

            $data[$fireId]['reaktorlar'][] = [
                'id' => $r['reaktor_id'],
                'fis_no' => $r['fis_no'],
                'miktar' => $r['miktar'],
                'karisik_fire' => $r['karisik_fire'],
                'farkli_madde' => $r['reaktor_farkli_madde'],
                'aciklama' => $r['aciklama'],
                'created_date' => $r['created_date'],
                'created_user' => $r['created_user'],
                'takozlar' => $takozlar  // 2. tablo için
            ];
        }

        return view('reaktorFireTakipView2', [
            'reaktorTakoz' => $data
        ]);
    }
}
