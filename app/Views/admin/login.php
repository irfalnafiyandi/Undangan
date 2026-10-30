<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — Admin Undangan</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="login">
<form method="post" action="<?= site_url('admin/login') ?>" class="panel narrow">
    <h1>Masuk</h1>
    <?php if (session('error')): ?><div class="flash err"><?= esc(session('error')) ?></div><?php endif ?>
    <?= csrf_field() ?>
    <label>Email <input type="email" name="email" required value="<?= esc(old('email')) ?>"></label>
    <label>Password <input type="password" name="password" required></label>
    <button class="btn" type="submit">Masuk</button>
</form>
</body>
</html>
