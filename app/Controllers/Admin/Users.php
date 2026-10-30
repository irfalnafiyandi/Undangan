<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\InvitationModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/** Manajemen akun klien (hanya admin). */
class Users extends BaseController
{
    public function __construct()
    {
        if (session()->get('user_role') !== 'admin') {
            throw PageNotFoundException::forPageNotFound();
        }
    }

    public function index(): string
    {
        return view('admin/users', ['users' => (new UserModel())->orderBy('id')->findAll()]);
    }

    public function create()
    {
        $rules = [
            'name'     => 'required|max_length[100]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[client,admin]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        (new UserModel())->insert([
            'name'     => trim($this->request->getPost('name')),
            'email'    => trim($this->request->getPost('email')),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => $this->request->getPost('role'),
        ]);

        return redirect()->to('/admin/users')->with('ok', 'Akun dibuat.');
    }

    public function delete(int $id)
    {
        if ($id === (int) session()->get('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'Tidak bisa menghapus akun sendiri.');
        }
        if ((new InvitationModel())->where('user_id', $id)->countAllResults() > 0) {
            return redirect()->to('/admin/users')->with('error', 'Akun masih memiliki undangan. Pindahkan atau hapus undangannya dulu.');
        }
        (new UserModel())->delete($id);

        return redirect()->to('/admin/users')->with('ok', 'Akun dihapus.');
    }
}
