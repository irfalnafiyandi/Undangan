<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($this->renderSection('title')) ?> — Admin Undangan</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body>
<header class="top">
    <a class="brand" href="<?= site_url('admin') ?>">💍 Admin Undangan</a>
    <nav>
        <a href="<?= site_url('admin') ?>">Undangan</a>
        <?php if (session('user_role') === 'admin'): ?><a href="<?= site_url('admin/users') ?>">Akun</a><?php endif ?>
        <span><?= esc(session('user_name')) ?></span>
        <a href="<?= site_url('admin/logout') ?>">Keluar</a>
    </nav>
</header>
<main class="wrap">
    <?php if (session('ok')): ?><div class="flash ok"><?= esc(session('ok')) ?></div><?php endif ?>
    <?php if (session('error')): ?><div class="flash err"><?= esc(session('error')) ?></div><?php endif ?>
    <?= $this->renderSection('content') ?>
</main>
</body>
</html>
