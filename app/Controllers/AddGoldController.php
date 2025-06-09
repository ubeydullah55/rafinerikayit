<?php

namespace App\Controllers;
use App\Models\CustomerModel;

class AddGoldController extends BaseController
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
         $customerModel = new CustomerModel();
        $data['customers'] = $customerModel->findAll();

        return view('addgold', $data);
    }



public function kaydet()
{
    $request = service('request');

    $altin_turu = $request->getPost('altin_turu'); // 0 = Hurda, 1 = Takoz

    $data = [
        'musteri' => $musteriId = $this->request->getPost('musteri_id'),
        'giris_gram' => floatval($request->getPost('giris_agirlik')),
        'tahmini_milyem' => floatval($request->getPost('tahmini_milyem')),
        'musteri_notu' => $request->getPost('musteri_notu'),
        'status_code' => 1, // Bekleme durumu
    ];

    // Modeli seç
    if ($altin_turu == '0') {
        $model = new \App\Models\HurdaModel(); // Hurda'ya ekle
    } else {
        $model = new \App\Models\TakozModel(); // Takoz'a ekle
    }

    if ($model->insert($data)) {
        return redirect()->back()->with('success', 'Kayıt başarıyla eklendi.');
    } else {
        return redirect()->back()->with('error', 'Kayıt eklenirken hata oluştu.');
    }
}



    public function savecustomer()
    {
        
        $model = new \App\Models\CustomerModel();
        $tc = $this->request->getPost('tc');

        // 1. Bu TC ile daha önce kayıt yapılmış mı kontrol et
        if($tc !='11111111111'){
            $existingCustomer = $model->where('tc', $tc)
                           ->where('status', 'A')
                           ->first();
    
            if ($existingCustomer) {
                // 2. Eğer varsa yönlendir ve mesaj göster
                return redirect()->to('/homepage')->with('error', 'Bu T.C. numarası ile kayıtlı bir müşteri zaten mevcut!');
            }
        }
    
        $uploadedFileNameOnyuz = 'default.png'; // Başta default resim
        $uploadedFileNameArkayuz = 'default.png'; // Başta default resim
        $createdDate = date('Y-m-d H:i:s');

        // Session'dan kullanıcı ID'sini alıyoruz
        $userId = session()->get('user_id');
        $onyuzFile = $this->request->getFile('onyuz_resim');
        if ($onyuzFile && $onyuzFile->getError() == 0) {
            if ($onyuzFile->isValid() && !$onyuzFile->hasMoved()) {
                $newNameOnyuz = uniqid() . '.' . $onyuzFile->getClientExtension();
                $onyuzFile->move(ROOTPATH . 'tccard', $newNameOnyuz);
                $uploadedFileNameOnyuz = $newNameOnyuz;
            }
        }

        $arkayuzFile = $this->request->getFile('arkayuz_resim');
        if ($arkayuzFile && $arkayuzFile->getError() == 0) {
            if ($arkayuzFile->isValid() && !$arkayuzFile->hasMoved()) {
                $newNameArkayuz = uniqid() . '.' . $arkayuzFile->getClientExtension();
                $arkayuzFile->move(ROOTPATH . 'tccard', $newNameArkayuz);
                $uploadedFileNameArkayuz = $newNameArkayuz;
            }
        }

        $data = [
            'ad'            => $this->request->getPost('ad'),
            'soyad'         => $this->request->getPost('soyad'),
            'tc'            => $this->request->getPost('tc'),
            'dogum_tarihi'  => $this->request->getPost('dogum_tarihi'),
            'dogum_yeri'    => $this->request->getPost('dogum_yeri'),
            'anne_adi'      => $this->request->getPost('anne_adi'),
            'baba_adi'      => $this->request->getPost('baba_adi'),
            'uyruk'         => $this->request->getPost('uyruk'),
            'kimlik_belgesi_turu'    => $this->request->getPost('belge_turu'),
            'kimlik_belgesi_numarası' => $this->request->getPost('belge_numarası'),
            'e_posta'        => $this->request->getPost('eposta'),
            'tel'           => $this->request->getPost('tel'),
            'meslek'        => $this->request->getPost('meslek'),
            'sehir'         => $this->request->getPost('sehir'),
            'adres'         => $this->request->getPost('adres'),
            'musteri_notu'  => $this->request->getPost('not'),
            'img_1'         => $uploadedFileNameOnyuz,
            'img_2'         => $uploadedFileNameArkayuz,
            'created_date'                   => $createdDate,
            'ekleyen_id'                   => $userId,
        ];

        
        $model->insert($data);
        return redirect()->to('/homepage')->with('success', 'Müşteri başarıyla eklendi.');
    }



    public function deletecustomer($id)
    {
        $customerModel = new \App\Models\CustomerModel(); // Model ismini senin kullandığın modele göre değiştir.

        // Müşterinin verisini silmek yerine status'ü güncelliyoruz
        $session = session(); // Oturumu başlatıyoruz

        // Oturumdan kullanıcı ID'sini alıyoruz
        $userId = $session->get('user_id');

        // Şu anki tarihi alıyoruz
        $currentDate = date('Y-m-d H:i:s'); // Yıl-Ay-Gün Saat:Dakika:Saniye formatında

        // Veriyi güncelliyoruz
        $customerModel->update($id, [
            'status' => 'P',        // Durum 'P' olarak güncelleniyor
            'created_date' => $currentDate, // 'created_date' şu anki zamanla güncelleniyor
            'ekleyen_id' => $userId  // 'ekleyen_id' oturumdan alınan kullanıcı ID'siyle güncelleniyor
        ]);

        return redirect()->to('/homepage')->with('success', 'Müşteri başarıyla silindi.');
    }

    public function customerview($id)
    {
        $customerModel = new \App\Models\CustomerModel();
        $userModel = new \App\Models\UserModel();
    
        $customer = $customerModel->find($id);
    
        if (!$customer) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Müşteri bulunamadı.');
        }
    
        // Ekleyen kullanıcıyı bul
        $ekleyenUser = $userModel->find($customer['ekleyen_id']);
        $ekleyenName = $ekleyenUser ? $ekleyenUser['name'] : 'Bilinmiyor';
    
        return view('viewcustomer', [
            'customer' => $customer,
            'ekleyenName' => $ekleyenName
        ]);
    }
    public function customereditview($id)
    {
        $customerModel = new \App\Models\CustomerModel();
        $customer = $customerModel->find($id); // id'ye göre müşteri verilerini al

        if (!$customer) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Müşteri bulunamadı.');
        }

        // Müşteri verisini view'e gönder
        return view('editcustomer', ['customer' => $customer]);
    }


    public function editcustomer($customerId)
    {
        $uploadedFileNameOnyuz = null;
        $uploadedFileNameArkayuz = null;

        $onyuzFile = $this->request->getFile('onyuz_resim');
        if ($onyuzFile && $onyuzFile->isValid() && !$onyuzFile->hasMoved()) {
            $newNameOnyuz = uniqid() . '.' . $onyuzFile->getClientExtension();
            $onyuzFile->move(ROOTPATH . 'tccard', $newNameOnyuz);

            $oldImage = $this->request->getPost('old_img_1');
            if ($oldImage && file_exists(ROOTPATH . 'tccard/' . $oldImage)) {
                unlink(ROOTPATH . 'tccard/' . $oldImage);
            }
            $uploadedFileNameOnyuz = $newNameOnyuz;
        } else {
            // Yeni resim yüklenmediyse eski resmi koru
            $uploadedFileNameOnyuz = $this->request->getPost('old_img_1');
        }

        $arkayuzFile = $this->request->getFile('arkayuz_resim');
        if ($arkayuzFile && $arkayuzFile->isValid() && !$arkayuzFile->hasMoved()) {
            $newNameArkayuz = uniqid() . '.' . $arkayuzFile->getClientExtension();
            $arkayuzFile->move(ROOTPATH . 'tccard', $newNameArkayuz);

            $oldImage2 = $this->request->getPost('old_img_2');
            if ($oldImage2 && file_exists(ROOTPATH . 'tccard/' . $oldImage2)) {
                unlink(ROOTPATH . 'tccard/' . $oldImage2);
            }
            $uploadedFileNameArkayuz = $newNameArkayuz;
        } else {
            // Yeni resim yüklenmediyse eski resmi koru
            $uploadedFileNameArkayuz = $this->request->getPost('old_img_2');
        }

        $data = [
            'ad'                      => $this->request->getPost('ad'),
            'soyad'                   => $this->request->getPost('soyad'),
            'tc'                      => $this->request->getPost('tc'),
            'dogum_tarihi'             => $this->request->getPost('dogum_tarihi'),
            'dogum_yeri'              => $this->request->getPost('dogum_yeri'),
            'anne_adi'                => $this->request->getPost('anne_adi'),
            'baba_adi'                => $this->request->getPost('baba_adi'),
            'uyruk'                   => $this->request->getPost('uyruk'),
            'kimlik_belgesi_turu'     => $this->request->getPost('belge_turu'),
            'kimlik_belgesi_numarası' => $this->request->getPost('belge_numarası'),
            'e_posta'                 => $this->request->getPost('eposta'),
            'tel'                     => $this->request->getPost('tel'),
            'meslek'                  => $this->request->getPost('meslek'),
            'sehir'                   => $this->request->getPost('sehir'),
            'adres'                   => $this->request->getPost('adres'),
            'musteri_notu'            => $this->request->getPost('not'),
            'img_1'                   => $uploadedFileNameOnyuz,
            'img_2'                   => $uploadedFileNameArkayuz,
        ];

        $model = new \App\Models\CustomerModel();
        $model->update($customerId, $data);
        return redirect()->to('/homepage')->with('success', 'Müşteri bilgileri başarıyla güncellendi.');
    }
}
