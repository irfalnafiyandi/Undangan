<?php /** @var ?array $demo */ ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Undangan Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
    <style>body{display:grid;place-items:center;min-height:100vh;text-align:center;background:#faf8f2}h1{font-family:'Great Vibes',cursive;font-size:3.5rem;color:#7f9b84;margin:0}</style>
</head>
<body>
    <div>
        <h1>Undangan Digital</h1>
        <p>Buat dan bagikan undangan pernikahan online untuk banyak pasangan.</p>
        <?php if ($demo): ?><p><a class="btn" href="<?= site_url('u/' . $demo['slug']) ?>">Lihat Contoh Undangan</a></p><?php endif ?>
        <p><a href="<?= site_url('admin') ?>">Masuk Panel Admin</a></p>
    </div>
</body>
</html>
