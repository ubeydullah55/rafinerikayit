<?php

namespace App\Controllers;
use App\Models\UserModel;
class LoginController extends BaseController
{
    public function index(): string
    {
        return view('login');
    }

    public function login()
    {
        $session = session();
        $model = new UserModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Kullanıcıyı username'e göre bul
        $user = $model->where('name', $username)->first();

        if ($user) {
            // Şifreyi md5 ile kontrol ediyoruz
            if ($user['password'] === md5($password)) {
                // Başarılı giriş
                $session->set([
                    'user_id' => $user['user_id'],
                    'name'    => $user['name'],
                    'role'    => $user['role'],
                    'logged_in' => true,
                ]);
                return redirect()->to('/homepage'); // yönlendir
            } else {
                // Şifre yanlış
                $session->setFlashdata('error', 'Şifreniz yanlış.');
                return redirect()->to('/');
            }
        } else {
            // Kullanıcı bulunamadı
           $session->setFlashdata('error', 'Kullanıcı bulunamadı.');
            return redirect()->to('/');
        }

    }

    public function logout()
{
    $session = session();
    $session->destroy(); // tüm session'ı sıfırlar
    return redirect()->to('/'); // ana dizine yönlendir
}
}
