<?php

namespace App\Controllers;

use App\Models\CesniModel;
use App\Models\HurdaModel;
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
        // Modeli yükleyelim

        $model = new TakozModel();
        $hurdamodel = new HurdaModel();
        // Tüm takozları veritabanından çek
        $items = $model->where('status_code', 1)->findAll();
        $hurdalar = $hurdamodel->where('status_code', 1)->findAll();

        // Toplam gramajı hesapla
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



        // Tüm takozları veritabanından çek

        $hurdalar = $hurdamodel->where('status_code', 2)->findAll();

        // Toplam gramajı hesapla
        $hurdatotalGram = array_sum(array_column($hurdalar, 'giris_gram'));



        // status_code = 2 olan takozları al
        $items = $model->where('status_code', 2)->findAll();

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
                $totalCesniGram += $item['cesni_has']+$item['kullanilan'];
            } else {
                $totalCesniGram += $item['agirlik'];
            }
        }

        $hastakozlar = $model->where('status_code', 5)->findAll();
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
            'hurdatotalGram' => $hurdatotalGram,
            'hastakozlar' => $hastakozlar,
            'totalHasTakozGram' => $totalHasTakozGram
        ]);
    }



    public function eritme()
    {
        $model = new TakozModel();
        $modelcesni = new CesniModel();
        $db = \Config\Database::connect();

        // status_code = 2 olan takozları al
        $items = $model->where('status_code', 3)->findAll();

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

                    if ($cesniData) {
                        $takozmodel->insert([
                            'musteri'            => $cesniData['musteri'] . ' ÇEŞNİ',
                            'giris_gram'         => $cesniData['cesni_has'],
                            'islem_goren_miktar'         => $cesniData['cesni_has'],
                            'tahmini_milyem'     => 999.9,
                            'status_code'        => 3,
                            'olculen_milyem'     => 999.9,
                            'musteri_notu'       => $cesniData['musteri_notu'],
                            'cesni_id'           => $id,
                            'tur'=>1 //çeşni olarak eklendi
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

            // Her takoz için yeni kayıt (sadece ağırlık bilgisi)
            foreach ($takozlar as $t) {
                $takozModel->insert([
                    'musteri' => 'HasTakoz',
                    'giris_gram' => $t['agirlik'],
                    'grup_kodu' => $groupCode,
                    'tahmini_milyem' => 999.9,
                    'status_code' => 5,
                    'tur'=>2 //hastakoz olarak eklendi
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

        $model = new TakozModel();
        $hurdamodel = new HurdaModel();
        // Tüm takozları veritabanından çek
        $items = $model->where('status_code', 4)
            ->where('hurda_grup_kodu <=', 0)
            ->where('tur =', 0)
            ->findAll();
        $hurdalar = $hurdamodel->where('status_code', 1)->findAll();

        // Toplam gramajı hesapla
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
        $data = $this->request->getJSON(true); // fetch ile JSON geldiği için
        $id = $data['id'] ?? null;

        if (!$id) {
            return $this->response->setStatusCode(400)->setBody("Geçersiz ID.");
        }

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
        $takoz = $takozModel->find($fisNo);

        if (!$takoz) {
            return $this->response->setBody("Takoz bilgisi bulunamadı.");
        }

        // 3. Grup kodunu al
        $grupKodu = $takoz['grup_kodu'];


        if ($takoz['status_code'] >= 5) {
            // 4. Bu grup koduna sahip tüm takozları al (geçmiş hareketler)
            $gecmis = $takozModel
                ->where('grup_kodu', $grupKodu)
                ->findAll();
        } else {
            $gecmis = [$takoz];
        }
        foreach ($gecmis as $t) {
            if (!empty($t['hurda_grup_kodu']) && $t['hurda_grup_kodu'] > 0) {
                $hurda = $hurdaModel->find($t['hurda_grup_kodu']);
                if ($hurda) {
                    // hurda verisini tamamlayalım
                    $hurda['id'] = 'H-' . $t['hurda_grup_kodu']; // farklı olsun diye H- prefix
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

        usort($gecmis, function ($a, $b) {
            $aHurda = str_starts_with($a['id'], 'H-') ? 0 : 1;
            $bHurda = str_starts_with($b['id'], 'H-') ? 0 : 1;
            return $aHurda <=> $bHurda;
        });
        // 5. Partial view döndür (incele_partial.php)
        return view('incele_partial', [
            'cesni' => $cesni,
            'gecmis' => $gecmis
        ]);
    }

    public function inceleTakoz()
    {
        $data = $this->request->getJSON(true); // fetch ile JSON geldiği için
        $id = $data['id'] ?? null;

        if (!$id) {
            return $this->response->setStatusCode(400)->setBody("Geçersiz ID.");
        }

        $cesniModel = new \App\Models\CesniModel();
        $takozModel = new \App\Models\TakozModel();
        $hurdaModel = new \App\Models\HurdaModel();
        // 1. Seçilen çeşniyi bul

        $takoz = $takozModel->find($id);


        if (!$takoz) {
            return $this->response->setBody("Kayıt bulunamadı.");
        }

        // 2. Fis_no'dan takozu bul


        // 3. Grup kodunu al
        $grupKodu = $takoz['grup_kodu'];


        if ($takoz['tur'] != 1) {
            // 4. Bu grup koduna sahip tüm takozları al (geçmiş hareketler)
            $gecmis = $takozModel
                ->where('grup_kodu', $grupKodu)
                ->findAll();
        } else {
            $gecmis = [$takoz];
        }



        $reaktorFire=0;
        $eldekiToplamHas=0;
        $cikanHasTakoz=0;
        foreach ($gecmis as $t) {
          if($t['tur']==0){
            $eldekiToplamHas+=($t['islem_goren_miktar']*$t['olculen_milyem'])/1000;
          }
           if($t['tur']==1){
            $eldekiToplamHas+=$t['giris_gram'];
          }
          if($t['tur']==2)
          {
            $cikanHasTakoz+=$t['giris_gram'];
          }
        }
        $reaktorFire=$eldekiToplamHas -$cikanHasTakoz;

        $eritmeFire=0;
        foreach ($gecmis as $t) {
            if (!empty($t['hurda_grup_kodu']) && $t['hurda_grup_kodu'] > 0) {
                $hurda = $hurdaModel->find($t['hurda_grup_kodu']);
               
                if ($hurda) {
                      if (isset($t['giris_gram']) && isset($hurda['giris_gram'])) {
                $eritmeFire += $hurda['giris_gram'] - $t['giris_gram'];
            }
                    // hurda verisini tamamlayalım
                    $hurda['id'] = 'H-' . $t['hurda_grup_kodu']; // farklı olsun diye H- prefix
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

        usort($gecmis, function ($a, $b) {
            $aHurda = str_starts_with($a['id'], 'H-') ? 0 : 1;
            $bHurda = str_starts_with($b['id'], 'H-') ? 0 : 1;
            return $aHurda <=> $bHurda;
        });
        // 5. Partial view döndür (incele_partial.php)
        return view('incele_partial', [
            'gecmis' => $gecmis,
            'eritme_fire' =>$eritmeFire,
            'reaktor_fire'=>$reaktorFire
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
