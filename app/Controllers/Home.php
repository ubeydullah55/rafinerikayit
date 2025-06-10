<?php

namespace App\Controllers;

use App\Models\CesniModel;
use App\Models\HurdaModel;
use App\Models\ReaktorModel;
use App\Models\TakozModel;
use CodeIgniter\Model;

class Home extends BaseController
{
    public function __construct()
    {
        if (!session()->get('logged_in')) {
            header('Location: ' . base_url('/'));
            exit();
        }
    }

    public function index()
    {
        $db = \Config\Database::connect();

        // TAKOZLAR: Customer tablosuyla join
        $builder = $db->table('takozlar');
        $builder->select('takozlar.*, customer.ad as musteri_adi');
        $builder->join('customer', 'customer.id = takozlar.musteri', 'left');
        $builder->where('takozlar.status_code', 1);
        $items = $builder->get()->getResultArray();

        // HURDALAR: Customer tablosuyla join
        $hurdaBuilder = $db->table('hurda');
        $hurdaBuilder->select('hurda.*, customer.ad as musteri_adi');
        $hurdaBuilder->join('customer', 'customer.id = hurda.musteri', 'left');
        $hurdaBuilder->where('hurda.status_code', 1);
        $hurdalar = $hurdaBuilder->get()->getResultArray();

        // Toplamlar
        $totalGram = array_sum(array_column($items, 'giris_gram'));
        $hurdatotalGram = array_sum(array_column($hurdalar, 'giris_gram'));

        return view('homepage', [
            'items' => $items,
            'totalGram' => $totalGram,
            'hurdalar' => $hurdalar,
            'hurdatotalGram' => $hurdatotalGram,
            'role' => session()->get('role'),
        ]);
    }


    public function ayarevi()
    {
        $model = new TakozModel();
        $modelcesni = new CesniModel();
        $hurdamodel = new HurdaModel();
        $db = \Config\Database::connect();

        $reaktorModel = new ReaktorModel();
        $reaktorFireTotalModel = $reaktorModel
            ->selectSum('miktar')
            ->first();

        // TAKOZLAR: status_code=2 olanlar + customer tablosu join
        $takozBuilder = $db->table('takozlar');
        $takozBuilder->select('takozlar.*, customer.ad as musteri_adi');
        $takozBuilder->join('customer', 'customer.id = takozlar.musteri', 'left');
        $takozBuilder->where('takozlar.status_code', 2);
        $items = $takozBuilder->get()->getResultArray();

        // HURDALAR: status_code=2 olanlar + customer tablosu join
        $hurdaBuilder = $db->table('hurda');
        $hurdaBuilder->select('hurda.*, customer.ad as musteri_adi');
        $hurdaBuilder->join('customer', 'customer.id = hurda.musteri', 'left');
        $hurdaBuilder->where('hurda.status_code', 2);
        $hurdalar = $hurdaBuilder->get()->getResultArray();

        // CESNİLER: status_code=1 olanlar + takozlar + customer join
        $cesniBuilder = $db->table('cesni');
        $cesniBuilder->select('cesni.*, takozlar.musteri, takozlar.giris_gram, takozlar.tahmini_milyem, takozlar.olculen_milyem, takozlar.cesni_has, customer.ad as musteri_adi');
        $cesniBuilder->join('takozlar', 'cesni.fis_no = takozlar.id');
        $cesniBuilder->join('customer', 'customer.id = takozlar.musteri', 'left');
        $cesniBuilder->where('cesni.status_code', 1);
        $cesnibilgi = $cesniBuilder->get()->getResultArray();

        // Toplam gramaj hesaplama (takozlar)
        $totalGram = 0;
        foreach ($items as $item) {
            if ($item['islem_goren_miktar'] > 0) {
                $totalGram += $item['islem_goren_miktar'];
            } else {
                $totalGram += $item['giris_gram'];
            }
        }

        // Toplam gramaj hesaplama (cesniler)
        $totalCesniGram = 0;
        foreach ($cesnibilgi as $item) {
            if ($item['cesni_has'] > 0) {
                $totalCesniGram += $item['cesni_has'] + ($item['agirlik'] - $item['kullanilan']);
            } else {
                $totalCesniGram += $item['agirlik'];
            }
        }

        // HASTAKOZLAR (status_code=5) + customer join
        $hastakozBuilder = $db->table('takozlar');
        $hastakozBuilder->select('takozlar.*, customer.ad as musteri_adi');
        $hastakozBuilder->join('customer', 'customer.id = takozlar.musteri', 'left');
        $hastakozBuilder->where('takozlar.status_code', 5);
        $hastakozlar = $hastakozBuilder->get()->getResultArray();

        $totalHasTakozGram = 0;
        foreach ($hastakozlar as $item) {
            if ($item['cesni_has'] > 0) {
                $totalHasTakozGram += $item['islem_goren_miktar'];
            } else {
                $totalHasTakozGram += $item['giris_gram'];
            }
        }

        return view('ayarevi', [
            'items' => $items,
            'totalGram' => $totalGram,
            'role' => session()->get('role'),
            'cesnibilgi' => $cesnibilgi,
            'totalCesni' => $totalCesniGram,
            'hurdalar' => $hurdalar,
            'hurdatotalGram' => array_sum(array_column($hurdalar, 'giris_gram')),
            'hastakozlar' => $hastakozlar,
            'totalHasTakozGram' => $totalHasTakozGram,
            'reaktorToplamFire' => $reaktorFireTotalModel
        ]);
    }




    public function eritme()
    {
        $db = \Config\Database::connect();

        // Takozlar modeli
        $model = new \App\Models\TakozModel();

        // status_code = 3 olan takozları müşteri adıyla alalım
        $builder = $db->table('takozlar');
        $builder->select('takozlar.*, customer.ad as musteri_adi');
        $builder->join('customer', 'customer.id = takozlar.musteri', 'left');
        $builder->where('takozlar.status_code', 3);
        $items = $builder->get()->getResultArray();

        // Cesni bilgisi, takozlar ile join ve müşteri adı ile
        $builder = $db->table('cesni');
        $builder->select('cesni.*, takozlar.musteri, takozlar.giris_gram, takozlar.tahmini_milyem, takozlar.olculen_milyem, takozlar.cesni_has, customer.ad as musteri_adi');
        $builder->join('takozlar', 'cesni.fis_no = takozlar.id');
        $builder->join('customer', 'customer.id = takozlar.musteri', 'left');
        $builder->where('cesni.status_code', 1);

        $cesnibilgi = $builder->get()->getResultArray();

        // Gram toplamlarını hesapla
        $totalGram = 0;
        foreach ($items as $item) {
            if ($item['islem_goren_miktar'] > 0) {
                $totalGram += $item['islem_goren_miktar'];
            } else {
                $totalGram += $item['giris_gram'];
            }
        }

        $totalCesniGram = 0;
        foreach ($cesnibilgi as $item) {
            if ($item['cesni_has'] > 0) {
                $totalCesniGram += $item['cesni_has'];
            } else {
                // Eğer 'agirlik' alanı yoksa 'giris_gram' olabilir diye kontrol ekleyebilirsin
                $totalCesniGram += $item['agirlik'] ?? 0;
            }
        }

        return view('eritme', [
            'items' => $items,
            'totalGram' => $totalGram,
            'role' => session()->get('role'),
            'cesnibilgi' => $cesnibilgi,
            'totalCesni' => $totalCesniGram,
        ]);
    }




    public function cesniEkle()
    {
        if ($this->request->isAJAX()) {
            $input = $this->request->getJSON(true);

            $id = $input['id']; // bu takoz tablosundaki ID
            $eklenecekCesni = floatval($input['cesni_gram']);

            $model = new \App\Models\TakozModel();
            $record = $model->find($id);

            if (!$record) {
                return $this->response->setJSON(['success' => false, 'message' => 'Kayıt bulunamadı']);
            }

            // Takip: mevcut değerleri alıp güncelle
            $yeniCesni = $record['cesni_gram'] + $eklenecekCesni;
            $islemGoren = $record['giris_gram'] - $yeniCesni;

            // Güncelleme işlemi
            $updateSuccess = $model->update($id, [
                'cesni_gram' => $yeniCesni,
                'islem_goren_miktar' => $islemGoren
            ]);

            // Cesni tablosuna yeni kayıt ekleyelim
            $cesniModel = new \App\Models\CesniModel();
            $insertSuccess = $cesniModel->insert([
                'fis_no' => $id, // takoz tablosundaki ID ile eşleşir
                'agirlik' => $eklenecekCesni,
                'status_code' => 1 // aktif olarak işaretliyoruz
            ]);

            return $this->response->setJSON([
                'success' => $updateSuccess && $insertSuccess
            ]);
        }

        return $this->response->setJSON(['success' => false]);
    }



    public function kalancesniEkle()
    {
        if ($this->request->isAJAX()) {
            $input = $this->request->getJSON(true);

            $id = $input['id']; // takoz ID
            $tableid = $input['tableid']; // çeşniid
            $kullanilanCesni = floatval($input['kullanilan_gram']);
            $kalanHasCesni = floatval($input['cesni_gram']);

            $model = new \App\Models\TakozModel();
            $reaktorModel = new \App\Models\ReaktorModel();
            $record = $model->find($id);
            $modelCesni = new \App\Models\CesniModel();
            $modelCesni->update($tableid, [
                'kullanilan' => $kullanilanCesni
            ]);

            if (!$record) {
                return $this->response->setJSON(['success' => false, 'message' => 'Kayıt bulunamadı']);
            }

            // Mevcut verileri çek
            $mevcutCesniGram = $record['cesni_gram']; // dokunulmayacak


            // Yeni değerleri hesapla


            $olculenMilyem = ($kalanHasCesni / $kullanilanCesni) * 1000;
            // Güncelleme yap
            $updateSuccess = $model->update($id, [
                'cesni_has' => $kalanHasCesni,
                'olculen_milyem' => $olculenMilyem
            ]);

            if ($record['tur'] == 2) {
                $reaktorFire = 0;
                $eldekiToplamHas = 0;
                $cikanHasTakoz = 0;

                $gecmis = $model
                    ->where('grup_kodu', $record['grup_kodu'])
                    ->findAll();

                $fisIdler = array_column($gecmis, 'id');

                // Daha önce reaktöre insert yapıldı mı kontrol et
                $adet = $reaktorModel
                    ->whereIn('fis_no', $fisIdler)
                    ->countAllResults();

                if ($adet == 0) {
                    // Daha önce kayıt yapılmamış, insert atabiliriz
                    foreach ($gecmis as $t) {
                        if ($t['tur'] == 0) {
                            $eldekiToplamHas += ($t['islem_goren_miktar'] * $t['olculen_milyem']) / 1000;
                        }
                        if ($t['tur'] == 1) {
                            $eldekiToplamHas += $t['giris_gram'];
                        }
                        if ($t['tur'] == 2) {
                            $cikanHasTakoz += $t['giris_gram'];
                        }
                    }

                    $reaktorFire = $eldekiToplamHas - $cikanHasTakoz;

                    $data = [
                        'fis_no' => $id,
                        'miktar' => $reaktorFire
                    ];

                    $reaktorModel->insert($data);
                }
            }

            return $this->response->setJSON(['success' => $updateSuccess]);
        }

        return $this->response->setJSON(['success' => false]);
    }


    public function ilerletAjax($id)
    {
        if ($this->request->isAJAX()) {
            $model = new \App\Models\TakozModel();

            // Önce mevcut kaydı al
            $current = $model->find($id);

            if ($current) {
                // Mevcut status_code'yi bir artır
                $newStatus = $current['status_code'] + 1;

                // Güncelle
                $updated = $model->update($id, ['status_code' => $newStatus]);

                return $this->response->setJSON([
                    'success' => $updated ? true : false,
                    'new_status_code' => $newStatus
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Kayıt bulunamadı'
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'error' => 'AJAX değil'
        ]);
    }


    public function ilerletAjaxHurda($id)
    {
        if ($this->request->isAJAX()) {
            $model = new \App\Models\HurdaModel();

            // Önce mevcut kaydı al
            $current = $model->find($id);

            if ($current) {
                // Mevcut status_code'yi bir artır
                $newStatus = $current['status_code'] + 1;

                // Güncelle
                $updated = $model->update($id, ['status_code' => $newStatus]);

                return $this->response->setJSON([
                    'success' => $updated ? true : false,
                    'new_status_code' => $newStatus
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Kayıt bulunamadı'
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'error' => 'AJAX değil'
        ]);
    }

    public function ilerletCesniAjax($id)
    {
        if ($this->request->isAJAX()) {
            $model = new \App\Models\CesniModel();
            $takozmodel = new \App\Models\TakozModel();

            // Önce mevcut çeșni kaydını al
            $current = $model->find($id);

            if ($current) {
                // Mevcut status_code'yi bir artır
                $newStatus = $current['status_code'] + 1;

                // Güncelle
                $updated = $model->update($id, ['status_code' => $newStatus]);

                // ✅ TakozModel'e yeni kayıt ekle
                // CesniModel içinden fis_no alınıyor ve ona göre CesniModel'den kayıt çekiliyor
                $fisNo = $current['fis_no'] ?? null;

                if ($fisNo) {
                    // Aynı fis_no'ya ait kayıtları bul
                    $cesniData = $takozmodel->where('id', $fisNo)->first();
                    $ortalamaCesniHas = ((($current['agirlik'] - $current['kullanilan']) * $cesniData['olculen_milyem']) / 1000) + $cesniData['cesni_has'];
                    if ($cesniData) {
                        $takozmodel->insert([
                            'musteri'            => $cesniData['musteri'] . ' ÇEŞNİ',
                            'giris_gram'         => $ortalamaCesniHas,
                            'islem_goren_miktar'         => $ortalamaCesniHas,
                            'tahmini_milyem'     => 999.9,
                            'status_code'        => 3,
                            'olculen_milyem'     => 999.9,
                            'musteri_notu'       => $cesniData['musteri_notu'],
                            'cesni_id'           => $id,
                            'tur' => 1 //çeşni olarak eklendi
                        ]);
                    }
                }

                return $this->response->setJSON([
                    'success' => $updated ? true : false,
                    'new_status_code' => $newStatus
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'error' => 'Kayıt bulunamadı'
                ]);
            }
        }

        return $this->response->setJSON([
            'success' => false,
            'error' => 'AJAX değil'
        ]);
    }



    public function uretTakoz()
    {
        if ($this->request->isAJAX()) {
            $data = $this->request->getJSON(true);

            $ids = $data['ids'] ?? [];
            $takozlar = $data['takozlar'] ?? [];

            if (empty($ids) || empty($takozlar)) {
                return $this->response->setJSON(['success' => false, 'error' => 'Eksik veri gönderildi.']);
            }

            $takozModel = new \App\Models\TakozModel();

            // Veritabanındaki en büyük grup_kodu'nu al
            $sonGrupKodu = (int) ($takozModel->selectMax('grup_kodu')->first()['grup_kodu'] ?? 0);
            $groupCode = $sonGrupKodu + 1;
            $userAd = session()->get('name');
            // Her takoz için yeni kayıt (sadece ağırlık bilgisi)
            foreach ($takozlar as $t) {
                $takozModel->insert([
                    'musteri' => 2291,
                    'giris_gram' => $t['agirlik'],
                    'grup_kodu' => $groupCode,
                    'tahmini_milyem' => 999.9,
                    'status_code' => 5,
                    'tur' => 2, //hastakoz olarak eklendi
                    'created_user' => $userAd,
                    'created_date' => date('Y-m-d H:i:s'),
                ]);
            }

            // Seçilen id'lerin grup kodunu ve durumunu güncelle
            foreach ($ids as $id) {
                $takozModel->update($id, [
                    'grup_kodu' => $groupCode,
                    'status_code' => 4 // İşlendi olarak işaretle
                ]);
            }

            return $this->response->setJSON(['success' => true]);
        }

        return $this->response->setJSON(['success' => false, 'error' => 'AJAX değil']);
    }


    public function hurdaTakozYap()
    {
        $hurdaId = $this->request->getPost('hurda_id');
        $adet = $this->request->getPost('adet');
        $agirliklar = $this->request->getPost('takoz_agirlik');

        // Modelleri yükle
        $hurdaModel = new \App\Models\HurdaModel();
        $takozModel = new \App\Models\TakozModel();

        // Hurda verisini al
        $hurda = $hurdaModel->find($hurdaId);
        $userAd = session()->get('name'); 
        if (!$hurda) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Hurda kaydı bulunamadı.'
            ]);
        }

        if (!is_array($agirliklar) || count($agirliklar) != $adet) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Eksik veya hatalı ağırlık bilgisi.'
            ]);
        }

        // Grup kodu oluştur (aynı grup altında olacaklar için)
        //$grupKodu = strtoupper(random_string('alnum', 8));

        // Her takoz için insert yap
        foreach ($agirliklar as $agirlik) {
            $takozModel->insert([
                'musteri'      => $hurda['musteri'],
                'giris_gram'      => $agirlik,
                'tahmini_milyem'       => $hurda['tahmini_milyem'], // varsayım
                'musteri_notu'  => $hurda['musteri_notu'],   // örnek alan
                'status_code'  => 2,   // örnek alan
                'hurda_grup_kodu' => $hurdaId,
                'created_user' => $userAd, 
                'created_date' => date('Y-m-d H:i:s'),
            ]);
        }

        // İstersen hurda kaydını güncelle (örn: grup_kodu ekle)
        $hurdaModel->update($hurdaId, [
            'status_code' => 3
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => $adet . ' adet takoz başarıyla eklendi.'
        ]);
    }




    public function silinenler()
    {
        // Modeli yükleyelim
        $model = new \App\Models\CustomerModel();

        // Veritabanından id'ye göre büyükten küçüğe sıralı verileri alalım
        $customers = $model->where('status', 'P')->orderBy('id', 'DESC')->findAll(); // DESC ile büyükten küçüğe sıralama

        // View'a verileri göndereceğiz
        return view('homepage', ['customers' => $customers]);
    }


    public function kasaHesap()
    {
        $db = \Config\Database::connect();

        // Takozlar: status_code=4, hurda_grup_kodu <=0, tur=0, müşteri adı ile birlikte
        $builder = $db->table('takozlar');
        $builder->select('takozlar.*, customer.ad as musteri_adi,customer.oran');
        $builder->join('customer', 'customer.id = takozlar.musteri', 'left');
        $builder->where('takozlar.status_code', 4);
        $builder->where('takozlar.hurda_grup_kodu <=', 0);
        $builder->where('takozlar.tur', 0);
        $items = $builder->get()->getResultArray();

        // Hurdalar: status_code=1, müşteri adı ile
        $hurdaBuilder = $db->table('hurda');
        $hurdaBuilder->select('hurda.*, customer.ad as musteri_adi');
        $hurdaBuilder->join('customer', 'customer.id = hurda.musteri', 'left');
        $hurdaBuilder->where('hurda.status_code', 1);
        $hurdalar = $hurdaBuilder->get()->getResultArray();

        // Toplam gramaj hesapları
        $totalGram = array_sum(array_column($items, 'giris_gram'));
        $hurdatotalGram = array_sum(array_column($hurdalar, 'giris_gram'));

        return view('kasaHesap', [
            'items' => $items,
            'totalGram' => $totalGram,
            'hurdalar' => $hurdalar,
            'hurdatotalGram' => $hurdatotalGram,
            'role' => session()->get('role'),
        ]);
    }




    public function inceleCesni()
    {
        $data = $this->request->getJSON(true);
        $id = $data['id'] ?? null;

        if (!$id) {
            return $this->response->setStatusCode(400)->setBody("Geçersiz ID.");
        }

        $db = \Config\Database::connect();
        $cesniModel = new \App\Models\CesniModel();
        $takozModel = new \App\Models\TakozModel();
        $hurdaModel = new \App\Models\HurdaModel();

        // 1. Seçilen çeşniyi bul
        $cesni = $cesniModel->find($id);

        if (!$cesni) {
            return $this->response->setBody("Kayıt bulunamadı.");
        }

        // 2. Fis_no'dan takozu bul
        $fisNo = $cesni['fis_no'];

        // Takozları müşteri adıyla alalım
        $builder = $db->table('takozlar');
        $builder->select('takozlar.*, customer.ad as musteri_adi');
        $builder->join('customer', 'customer.id = takozlar.musteri', 'left');
        $builder->where('takozlar.id', $fisNo);
        $takoz = $builder->get()->getRowArray();

        if (!$takoz) {
            return $this->response->setBody("Takoz bilgisi bulunamadı.");
        }

        // 3. Grup kodunu al
        $grupKodu = $takoz['grup_kodu'];

        if ($takoz['status_code'] >= 5) {
            // Grup koduna sahip tüm takozları müşteri adıyla al
            $builder = $db->table('takozlar');
            $builder->select('takozlar.*, customer.ad as musteri_adi');
            $builder->join('customer', 'customer.id = takozlar.musteri', 'left');
            $builder->where('takozlar.grup_kodu', $grupKodu);
            $gecmis = $builder->get()->getResultArray();
        } else {
            $gecmis = [$takoz];
        }

        // Hurda kayıtlarını müşteri adıyla ekleyelim
        $cacheHurdaGrupKodu = 0;
        foreach ($gecmis as $t) {
            if (!empty($t['hurda_grup_kodu']) && $t['hurda_grup_kodu'] > 0) {
                $hurdaBuilder = $db->table('hurda');
                $hurdaBuilder->select('hurda.*, customer.ad as musteri_adi');
                $hurdaBuilder->join('customer', 'customer.id = hurda.musteri', 'left');
                $hurdaBuilder->where('hurda.id', $t['hurda_grup_kodu']);
                $hurda = $hurdaBuilder->get()->getRowArray();

                if ($hurda) {
                    if ($cacheHurdaGrupKodu != $t['hurda_grup_kodu']) {
                        $cacheHurdaGrupKodu = $t['hurda_grup_kodu'];

                        $hurda['id'] = 'H-' . $t['hurda_grup_kodu'];
                        $hurda['musteri'] = $hurda['musteri'] . ' (Hurda)';
                        $hurda['agirlik'] = $hurda['giris_gram'] ?? 0;
                        $hurda['tahmini_milyem'] = $hurda['tahmini_milyem'] ?? '-';
                        $hurda['olculen_milyem'] = 0;
                        $hurda['cesni_has'] = 0;
                        $hurda['cesni_gram'] = 0;
                        $hurda['islem_goren_miktar'] = 0;
                        $hurda['grup_kodu'] = 0;
                        $hurda['hurda_grup_kodu'] = 0;
                        $hurda['cesni_id'] = 0;
                        $hurda['musteri_notu'] = $hurda['musteri_notu'] ?? '';
                       
                        array_push($gecmis, $hurda);
                    }
                }
            }
        }

        usort($gecmis, function ($a, $b) {
            $aHurda = str_starts_with($a['id'], 'H-') ? 0 : 1;
            $bHurda = str_starts_with($b['id'], 'H-') ? 0 : 1;
            return $aHurda <=> $bHurda;
        });

        return view('incele_partial', [
            'cesni' => $cesni,
            'gecmis' => $gecmis
        ]);
    }


    public function inceleTakoz()
    {
        $data = $this->request->getJSON(true);
        $id = $data['id'] ?? null;

        if (!$id) {
            return $this->response->setStatusCode(400)->setBody("Geçersiz ID.");
        }

        $db = \Config\Database::connect();
        $takozModel = new \App\Models\TakozModel();
        $hurdaModel = new \App\Models\HurdaModel();

        $takoz = $takozModel->find($id);

        if (!$takoz) {
            return $this->response->setBody("Kayıt bulunamadı.");
        }

        $grupKodu = $takoz['grup_kodu'];

        // Grup koduna göre takozları customer tablosundan müşteri adı ile beraber çekelim
        if ($takoz['tur'] != 1) {
            $builder = $db->table('takozlar');
            $builder->select('takozlar.*, customer.ad as musteri_adi');
            $builder->join('customer', 'customer.id = takozlar.musteri', 'left');
            $builder->where('takozlar.grup_kodu', $grupKodu);
            $gecmis = $builder->get()->getResultArray();
        } else {
            // tek kayıt olduğunda da müşteri adını ekleyelim
            $builder = $db->table('takozlar');
            $builder->select('takozlar.*, customer.ad as musteri_adi');
            $builder->join('customer', 'customer.id = takozlar.musteri', 'left');
            $builder->where('takozlar.id', $id);
            $gecmis = $builder->get()->getResultArray();
        }

        // Hesaplamalar ve hurda ekleme kısmı aynı, hurda verisini de müşteri adıyla ekleyelim
        $reaktorFire = 0;
        $eldekiToplamHas = 0;
        $cikanHasTakoz = 0;
        foreach ($gecmis as $t) {
            if ($t['tur'] == 0) {
                $eldekiToplamHas += ($t['islem_goren_miktar'] * $t['olculen_milyem']) / 1000;
            }
            if ($t['tur'] == 1) {
                $eldekiToplamHas += $t['giris_gram'];
            }
            if ($t['tur'] == 2) {
                $cikanHasTakoz += $t['giris_gram'];
            }
        }
        $reaktorFire = $eldekiToplamHas - $cikanHasTakoz;

        $eritmeFire = 0;
        $cacheHurdaGrupKodu = 0;
        $toplamHurda = 0;
        $toplamTakoz = 0;
        foreach ($gecmis as $t) {
            if (!empty($t['hurda_grup_kodu']) && $t['hurda_grup_kodu'] > 0) {
                // Hurda kaydını müşteri adı ile birlikte alalım
                $hurdaBuilder = $db->table('hurda');
                $hurdaBuilder->select('hurda.*, customer.ad as musteri_adi');
                $hurdaBuilder->join('customer', 'customer.id = hurda.musteri', 'left');
                $hurdaBuilder->where('hurda.id', $t['hurda_grup_kodu']);
                $hurda = $hurdaBuilder->get()->getRowArray();

                if ($hurda) {
                    if ($cacheHurdaGrupKodu != $t['hurda_grup_kodu']) {
                        $toplamHurda += $hurda['giris_gram'];
                        $cacheHurdaGrupKodu = $t['hurda_grup_kodu'];

                        // Hurda verisini tamamlayalım
                        $hurda['id'] = 'H-' . $t['hurda_grup_kodu'];
                        $hurda['musteri'] = $hurda['musteri'] . ' (Hurda)';
                        $hurda['agirlik'] = $hurda['giris_gram'] ?? 0;
                        $hurda['tahmini_milyem'] = $hurda['tahmini_milyem'] ?? '-';
                        $hurda['olculen_milyem'] = 0;
                        $hurda['cesni_has'] = 0;
                        $hurda['cesni_gram'] = 0;
                        $hurda['islem_goren_miktar'] = 0;
                        $hurda['grup_kodu'] = 0;
                        $hurda['hurda_grup_kodu'] = 0;
                        $hurda['cesni_id'] = 0;
                        $hurda['musteri_notu'] = $hurda['musteri_notu'] ?? '';

                        array_push($gecmis, $hurda);
                    }
                    if (isset($t['giris_gram']) && isset($hurda['giris_gram'])) {
                        $toplamTakoz += $t['giris_gram'];
                    }
                }
            }
        }
        $eritmeFire = $toplamHurda - $toplamTakoz;

        usort($gecmis, function ($a, $b) {
            $aHurda = str_starts_with($a['id'], 'H-') ? 0 : 1;
            $bHurda = str_starts_with($b['id'], 'H-') ? 0 : 1;
            return $aHurda <=> $bHurda;
        });

        return view('incele_partial', [
            'gecmis' => $gecmis,
            'eritme_fire' => $eritmeFire,
            'reaktor_fire' => $reaktorFire
        ]);
    }



    public function islenecek()
    {
        $model = new TakozModel();
        $modelcesni = new CesniModel();
        $db = \Config\Database::connect();

        // status_code = 2 olan takozları al
        $items = $model->where('status_code', 6)->findAll();

        // status_code = 1 olan cesnilerle takozları joinleyelim
        $builder = $db->table('cesni');
        $builder->select('cesni.*, takozlar.musteri, takozlar.giris_gram, takozlar.tahmini_milyem,takozlar.olculen_milyem,takozlar.cesni_has');
        $builder->join('takozlar', 'cesni.fis_no = takozlar.id');
        $builder->where('cesni.status_code', 1);

        $cesnibilgi = $builder->get()->getResultArray();

        // Gram toplamlarını hesapla
        $totalGram = 0;

        foreach ($items as $item) {
            if ($item['islem_goren_miktar'] > 0) {
                $totalGram += $item['islem_goren_miktar'];
            } else {
                $totalGram += $item['giris_gram'];
            }
        }


        $totalCesniGram = 0;

        foreach ($cesnibilgi as $item) {
            if ($item['cesni_has'] > 0) {
                $totalCesniGram += $item['cesni_has'];
            } else {
                $totalCesniGram += $item['agirlik'];
            }
        }



        return view('islenecek', [
            'items' => $items,
            'totalGram' => $totalGram,
            'role' => session()->get('role'),
            'cesnibilgi' => $cesnibilgi,
            'totalCesni' => $totalCesniGram,
        ]);
    }
}
