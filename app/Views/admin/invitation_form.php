<?php
/** @var ?array $inv */
$v  = fn (string $k, $d = '') => old($k, $inv[$k] ?? $d);
$dt = fn (?string $s) => $s ? date('Y-m-d\TH:i', strtotime($s)) : '';
$bulan = fn ($s) => date('d/m/Y H:i', strtotime($s));
?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?><?= $inv ? 'Kelola Undangan' : 'Undangan Baru' ?><?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="row">
    <h1><?= $inv ? esc($inv['groom_nick'] . ' & ' . $inv['bride_nick']) : 'Undangan Baru' ?></h1>
    <?php if ($inv): ?><a class="btn ghost" target="_blank" href="<?= site_url('u/' . $inv['slug']) ?>">Lihat Undangan ↗</a><?php endif ?>
</div>

<form method="post" enctype="multipart/form-data" class="panel grid" action="<?= site_url($inv ? "admin/invitations/{$inv['id']}" : 'admin/invitations') ?>">
    <?= csrf_field() ?>
    <h2>Data Undangan</h2>
    <label>Mempelai Pria (nama lengkap) <input name="groom_name" required value="<?= esc($v('groom_name')) ?>"></label>
    <label>Nama panggilan <input name="groom_nick" required value="<?= esc($v('groom_nick')) ?>"></label>
    <label class="full">Orang tua pria <input name="groom_parents" value="<?= esc($v('groom_parents')) ?>" placeholder="Putra dari Bapak ... & Ibu ..."></label>
    <label>Foto pria <input type="file" name="groom_photo_file" accept="image/*"></label>
    <label>Mempelai Wanita (nama lengkap) <input name="bride_name" required value="<?= esc($v('bride_name')) ?>"></label>
    <label>Nama panggilan <input name="bride_nick" required value="<?= esc($v('bride_nick')) ?>"></label>
    <label class="full">Orang tua wanita <input name="bride_parents" value="<?= esc($v('bride_parents')) ?>" placeholder="Putri dari Bapak ... & Ibu ..."></label>
    <label>Foto wanita <input type="file" name="bride_photo_file" accept="image/*"></label>
    <label>Foto cover <input type="file" name="cover_photo_file" accept="image/*"></label>
    <label>Tanggal pernikahan <input type="datetime-local" name="wedding_date" required value="<?= esc(old('wedding_date', $dt($inv['wedding_date'] ?? null))) ?>"></label>
    <fieldset class="full tpl">
        <legend>Template</legend>
        <?php foreach (\App\Models\InvitationModel::TEMPLATES as $k => $t): ?>
        <label class="tpl-card">
            <input type="radio" name="theme" value="<?= $k ?>" <?= $v('theme', 'sage') === $k ? 'checked' : '' ?>>
            <span class="swatch"><?php foreach ($t['colors'] as $c): ?><i style="background:<?= $c ?>"></i><?php endforeach ?></span>
            <strong><?= esc($t['label']) ?></strong>
            <small><?= esc($t['desc']) ?></small>
            <?php if ($inv): ?><a href="<?= site_url('u/' . $inv['slug'] . '?theme=' . $k) ?>" target="_blank">Pratinjau ↗</a><?php endif ?>
        </label>
        <?php endforeach ?>
    </fieldset>
    <label>Slug URL <input name="slug" value="<?= esc($v('slug')) ?>" placeholder="otomatis dari nama"></label>
    <?php if ($owners): ?>
    <label>Pemilik
        <select name="user_id"><?php foreach ($owners as $o): ?>
            <option value="<?= $o['id'] ?>" <?= (int) $v('user_id', session('user_id')) === (int) $o['id'] ? 'selected' : '' ?>><?= esc($o['name']) ?> (<?= esc($o['email']) ?>)</option><?php endforeach ?></select>
    </label>
    <?php endif ?>
    <label class="full">Kata pembuka <textarea name="opening_text" rows="3"><?= esc($v('opening_text')) ?></textarea></label>
    <label class="full">Kutipan <textarea name="quote" rows="2"><?= esc($v('quote')) ?></textarea></label>
    <label>Sumber kutipan <input name="quote_source" value="<?= esc($v('quote_source')) ?>"></label>
    <label>Musik (URL atau path file mp3) <input name="music_url" value="<?= esc($v('music_url', 'assets/audio/canon-demo.mp3')) ?>"></label>
    <label>Link live streaming (YouTube) <input type="url" name="live_url" value="<?= esc($v('live_url')) ?>" placeholder="https://www.youtube.com/watch?v=..."></label>
    <label>Alamat kirim kado <textarea name="gift_address" rows="2"><?= esc($v('gift_address')) ?></textarea></label>
    <label class="full">Amplop digital (satu per baris: <code>Bank|No Rekening|Atas Nama</code>) <textarea name="gifts" rows="3"><?= esc($v('gifts')) ?></textarea></label>
    <label class="check"><input type="checkbox" name="is_published" value="1" <?= $v('is_published', 1) ? 'checked' : '' ?>> Tayangkan undangan</label>
    <div class="full"><button class="btn" type="submit">Simpan</button></div>
</form>

<?php if ($inv): $id = $inv['id']; $evTitles = array_column($events, 'title', 'id'); ?>

<section class="panel" id="acara">
    <h2>Acara</h2>
    <table>
        <?php foreach ($events as $e): ?>
        <tr><td><strong><?= esc($e['title']) ?></strong><br><small><?= $bulan($e['event_at']) ?> — <?= esc($e['venue']) ?></small></td>
            <td><form method="post" action="<?= site_url("admin/invitations/{$id}/events/{$e['id']}/delete") ?>"><?= csrf_field() ?><button class="link danger">Hapus</button></form></td></tr>
        <?php endforeach ?>
    </table>
    <form method="post" action="<?= site_url("admin/invitations/{$id}/events") ?>" class="grid">
        <?= csrf_field() ?>
        <label>Nama acara <input name="title" required placeholder="Akad Nikah"></label>
        <label>Waktu <input type="datetime-local" name="event_at" required></label>
        <label>Selesai (teks) <input name="end_time" placeholder="10:00 WIB"></label>
        <label>Tempat <input name="venue" required></label>
        <label>Alamat <input name="address"></label>
        <label>Link Google Maps <input type="url" name="map_url"></label>
        <div class="full"><button class="btn">Tambah Acara</button></div>
    </form>
</section>

<section class="panel" id="galeri">
    <h2>Galeri</h2>
    <div class="thumbs">
        <?php foreach ($gallery as $g): ?>
        <form method="post" action="<?= site_url("admin/invitations/{$id}/gallery/{$g['id']}/delete") ?>">
            <?= csrf_field() ?><img src="<?= base_url($g['file']) ?>" alt=""><button class="link danger">Hapus</button>
        </form>
        <?php endforeach ?>
    </div>
    <form method="post" enctype="multipart/form-data" action="<?= site_url("admin/invitations/{$id}/gallery") ?>">
        <?= csrf_field() ?>
        <input type="file" name="photos[]" accept="image/*" multiple required>
        <button class="btn">Unggah</button> <small>Maks. 4 MB per foto.</small>
    </form>
</section>

<section class="panel" id="tamu">
    <h2>Daftar Tamu &amp; Link Personal</h2>
    <table>
        <tr><th>Nama</th><th>Kuota</th><th>Link</th><th>Dibuka</th><th>Check-in</th><th></th></tr>
        <?php foreach ($guests as $g):
            $link = site_url("u/{$inv['slug']}/{$g['code']}");
            $wa   = 'https://wa.me/' . ($g['phone'] ? preg_replace('/^0/', '62', $g['phone']) : '') . '?text=' . rawurlencode(
                "Assalamu'alaikum {$g['name']},\n\nKami mengundang Anda ke pernikahan {$inv['groom_nick']} & {$inv['bride_nick']}.\nBuka undangan: {$link}\n\nTerima kasih."
            ); ?>
        <tr>
            <td><?= esc($g['name']) ?></td>
            <td><?= (int) $g['quota'] ?></td>
            <td><input readonly value="<?= esc($link) ?>" onclick="this.select()"></td>
            <td><?= $g['opened_at'] ? '✓' : '–' ?></td>
            <td><form method="post" action="<?= site_url("admin/invitations/{$id}/guests/{$g['id']}/checkin") ?>"><?= csrf_field() ?>
                <button class="link"><?= $g['checked_in_at'] ? '✓ ' . esc(date('H:i', strtotime($g['checked_in_at']))) : 'Check-in' ?></button></form></td>
            <td class="act"><a href="<?= esc($wa) ?>" target="_blank" rel="noopener">WhatsApp</a>
                <form method="post" action="<?= site_url("admin/invitations/{$id}/guests/{$g['id']}/delete") ?>"><?= csrf_field() ?><button class="link danger">Hapus</button></form></td>
        </tr>
        <?php endforeach ?>
    </table>
    <form method="post" action="<?= site_url("admin/invitations/{$id}/guests") ?>">
        <?= csrf_field() ?>
        <label>Tambah tamu (satu per baris: <code>Nama</code>, <code>Nama|08123456789</code> atau <code>Nama|08123456789|kuota</code>; kuota bawaan 2)
            <textarea name="guests" rows="4" required></textarea></label>
        <button class="btn">Tambah Tamu</button>
    </form>
</section>

<section class="panel" id="rsvp">
    <div class="row"><h2>RSVP &amp; Ucapan (<?= count($rsvps) ?>)</h2><a class="btn ghost" href="<?= site_url("admin/invitations/{$id}/rsvps.csv") ?>">Ekspor CSV</a></div>
    <p>Hadir: <strong><?= array_sum(array_column(array_filter($rsvps, fn ($r) => $r['attendance'] === 'hadir'), 'pax')) ?></strong> orang</p>
    <table>
        <tr><th>Nama</th><th>Status</th><th>Jml</th><th>Acara</th><th>Ucapan</th><th></th></tr>
        <?php foreach ($rsvps as $r): ?>
        <tr><td><?= esc($r['name']) ?></td><td><?= esc($r['attendance']) ?></td><td><?= (int) $r['pax'] ?></td>
            <td><?= esc(implode(', ', array_map(static fn ($i) => $evTitles[(int) $i] ?? '', array_filter(explode(',', (string) $r['event_ids']))))) ?></td><td><?= esc($r['message']) ?></td>
            <td><form method="post" action="<?= site_url("admin/invitations/{$id}/rsvps/{$r['id']}/delete") ?>"><?= csrf_field() ?><button class="link danger">Hapus</button></form></td></tr>
        <?php endforeach ?>
    </table>
</section>

<section class="panel">
    <form method="post" action="<?= site_url("admin/invitations/{$id}/delete") ?>" onsubmit="return confirm('Hapus undangan beserta seluruh datanya?')">
        <?= csrf_field() ?><button class="btn danger">Hapus Undangan</button>
    </form>
</section>
<?php endif ?>
<?= $this->endSection() ?>
