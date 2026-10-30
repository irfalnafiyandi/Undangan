<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/admin');
        }

        return view('admin/login');
    }

    public function attempt()
    {
        $user = (new UserModel())->where('email', trim((string) $this->request->getPost('email')))->first();

        if (! $user || ! password_verify((string) $this->request->getPost('password'), $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau password salah.');
        }

        session()->regenerate();
        session()->set(['user_id' => (int) $user['id'], 'user_name' => $user['name'], 'user_role' => $user['role']]);

        return redirect()->to('/admin');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/admin/login');
    }
}
