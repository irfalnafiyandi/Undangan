<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Akun<?= $this->endSection() ?>
<?= $this->section('content') ?>
<h1>Akun Pengguna</h1>
<div class="panel">
<table>
    <tr><th>Nama</th><th>Email</th><th>Peran</th><th></th></tr>
    <?php foreach ($users as $u): ?>
    <tr><td><?= esc($u['name']) ?></td><td><?= esc($u['email']) ?></td><td><?= esc($u['role']) ?></td>
        <td><form method="post" action="<?= site_url("admin/users/{$u['id']}/delete") ?>" onsubmit="return confirm('Hapus akun?')"><?= csrf_field() ?><button class="link danger">Hapus</button></form></td></tr>
    <?php endforeach ?>
</table>
</div>
<form method="post" action="<?= site_url('admin/users') ?>" class="panel grid">
    <h2>Tambah Akun</h2><?= csrf_field() ?>
    <label>Nama <input name="name" required></label>
    <label>Email <input type="email" name="email" required></label>
    <label>Password <input type="password" name="password" minlength="6" required></label>
    <label>Peran <select name="role"><option value="client">Klien</option><option value="admin">Admin</option></select></label>
    <button class="btn">Simpan</button>
</form>
<?= $this->endSection() ?>
