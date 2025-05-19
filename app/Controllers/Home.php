<?php

namespace App\Controllers;

use App\Models\CesniModel;
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

        // Tüm takozları veritabanından çek
        $items = $model->where('status_code', 1)->findAll();

        // Toplam gramajı hesapla
        $totalGram = array_sum(array_column($items, 'giris_gram'));

        return view('homepage', [
            'items' => $items,
            'totalGram' => $totalGram,
            'role' => session()->get('role'),
        ]);
    }

    public function ayarevi()
    {
        $model = new TakozModel();
        $modelcesni = new CesniModel();
        $db = \Config\Database::connect();

        // status_code = 2 olan takozları al
        $items = $model->where('status_code', 2)->findAll();

        // status_code = 1 olan cesnilerle takozları joinleyelim
        $builder = $db->table('cesni');
        $builder->select('cesni.*, takozlar.musteri, takozlar.giris_gram, takozlar.tahmini_milyem,takozlar.olculen_milyem');
        $builder->join('takozlar', 'cesni.fis_no = takozlar.id');
        $builder->where('cesni.status_code', 1);

        $cesnibilgi = $builder->get()->getResultArray();

        // Gram toplamlarını hesapla
        $totalGram = array_sum(array_column($items, 'giris_gram'));
        $totalCesniGram = array_sum(array_column($cesnibilgi, 'agirlik'));

        return view('ayarevi', [
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
        $kalanHasCesni = floatval($input['cesni_gram']);

        $model = new \App\Models\TakozModel();
        $record = $model->find($id);

        if (!$record) {
            return $this->response->setJSON(['success' => false, 'message' => 'Kayıt bulunamadı']);
        }

        // Mevcut verileri çek
        $mevcutCesniGram = $record['cesni_gram']; // dokunulmayacak
        

        // Yeni değerleri hesapla
       
        
$olculenMilyem = ($kalanHasCesni / $mevcutCesniGram) * 1000;
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

            $updated = $model->update($id, ['status_code' => 2]);

            return $this->response->setJSON([
                'success' => $updated ? true : false
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'error' => 'AJAX değil'
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
}
