<?php

namespace App\Controllers;

class PersonelController extends BaseController
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
        $model = new \App\Models\UserModel();

        // Veritabanından id'ye göre büyükten küçüğe sıralı verileri alalım
        $users = $model->findAll(); // DESC ile büyükten küçüğe sıralama

        // View'a verileri göndereceğiz
        return view('addpersonel', ['users' => $users]);
    }

    public function updateuser()
    {
        $userModel = new \App\Models\UserModel();

        $userId = $this->request->getPost('user_id');
        $data = [
            'name' => $this->request->getPost('name'),
            'role' => $this->request->getPost('role'),
        ];

        // Şifre boş değilse şifreyi de güncelle
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = md5($password); // Şifreyi hash'leyerek kaydediyoruz
        }

        $userModel->update($userId, $data);

        return redirect()->to('addpersonel')->with('success', 'Kullanıcı başarıyla güncellendi.');
    }

    public function insertuser()
    {
        $model = new \App\Models\UserModel();
    
        $name = $this->request->getPost('name');
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('role');
        
        // Kullanıcı adı ve şifre boş olmasın
        if (!empty($name) && !empty($password) && !empty($role)) {
            // Kullanıcı adıyla veritabanında arama yapıyoruz
            $existingUser = $model->where('name', $name)->first();
    
            if ($existingUser) {
                // Kullanıcı varsa, hata mesajı dönüyoruz
                return redirect()->back()->with('error', 'Bu kullanıcı adı zaten mevcut.');
            } else {
                // Kullanıcı yoksa, yeni kullanıcı ekliyoruz
                $data = [
                    'name' => $name,
                    'password' => md5($password), // Şifreyi md5 ile kaydediyoruz
                    'role' => $role,
                ];
                $model->insert($data);
                return redirect()->to(site_url('addpersonel'))->with('success', 'Kullanıcı başarıyla eklendi.');
            }
        } else {
            return redirect()->back()->with('error', 'Tüm alanları doldurun.');
        }
    }
    

    public function deleteuser($userId)
    {
        // UserModel'i yüklüyoruz
        $userModel = new \App\Models\UserModel();

        // Kullanıcıyı silme işlemi
        if ($userModel->delete($userId)) {
            return redirect()->to(site_url('addpersonel'))->with('success', 'Kullanıcı başarıyla silindi.');
        } else {
            return redirect()->to(site_url('addpersonel'))->with('error', 'Silme işlemi başarısız oldu.');
        }
    }
}
