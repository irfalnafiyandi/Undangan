<?php
/**
 * @var array  $inv
 * @var array  $events
 * @var array  $gallery
 * @var array  $wishes
 * @var string $guestName
 * @var ?string $guestCode
 * @var array  $gifts
 * @var int    $quota
 * @var ?string $liveEmbed
 */
$ts       = strtotime($inv['wedding_date']);
$bulan    = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$hari     = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$tgl      = fn (int $t): string => $hari[(int) date('w', $t)] . ', ' . date('j', $t) . ' ' . $bulan[(int) date('n', $t)] . ' ' . date('Y', $t);
$img      = fn (?string $p): string => $p ? base_url($p) : base_url('assets/img/dummy/cover.svg');
$title    = $inv['groom_nick'] . ' & ' . $inv['bride_nick'];
$music    = $inv['music_url'] ? (preg_match('#^https?://#', $inv['music_url']) ? $inv['music_url'] : base_url($inv['music_url'])) : '';
$first    = $events[0] ?? null;
$calendar = '';
if ($first) {
    $s        = gmdate('Ymd\THis\Z', strtotime($first['event_at']) - 7 * 3600); // WIB -> UTC
    $e        = gmdate('Ymd\THis\Z', strtotime($first['event_at']) - 7 * 3600 + 7200);
    $calendar = 'https://calendar.google.com/calendar/render?action=TEMPLATE&text=' . rawurlencode('Pernikahan ' . $title)
        . "&dates={$s}/{$e}&location=" . rawurlencode($first['venue'] . ', ' . $first['address']);
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>The Wedding of <?= esc($title) ?></title>
    <meta property="og:title" content="The Wedding of <?= esc($title) ?>">
    <meta property="og:description" content="<?= esc($tgl($ts)) ?> — Anda diundang ke pernikahan kami.">
    <meta property="og:image" content="<?= esc($img($inv['cover_photo'])) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Poppins:wght@300;400;500&family=Allura&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/invitation.css') ?>">
</head>
<body data-theme="<?= esc($inv['theme']) ?>" class="locked">

<!-- COVER -->
<section id="cover" style="background-image:url('<?= esc($img($inv['cover_photo'])) ?>')">
    <div class="cover-box">
        <p class="eyebrow">The Wedding of</p>
        <h1 class="script"><?= esc($inv['groom_nick']) ?> <span>&amp;</span> <?= esc($inv['bride_nick']) ?></h1>
        <p class="date"><?= esc($tgl($ts)) ?></p>
        <div class="to">
            <small>Kepada Yth. Bapak/Ibu/Saudara/i</small>
            <strong><?= $guestName !== '' ? esc($guestName) : 'Tamu Undangan' ?></strong>
            <?php if ($guestCode): ?><small>Berlaku untuk <?= (int) $quota ?> orang</small><?php endif ?>
        </div>
        <button id="open-btn" class="btn" type="button">Buka Undangan</button>
    </div>
</section>

<?php if ($music): ?>
    <audio id="bgm" src="<?= esc($music) ?>" loop preload="none"></audio>
    <button id="music-btn" class="music" type="button" aria-label="Putar/hentikan musik" hidden>♪</button>
<?php endif ?>

<main id="content" hidden>
    <section class="sec center hero">
        <p class="eyebrow">Save the Date</p>
        <h2 class="script big"><?= esc($inv['groom_nick']) ?> &amp; <?= esc($inv['bride_nick']) ?></h2>
        <p class="date"><?= esc($tgl($ts)) ?></p>
        <?php if ($inv['opening_text']): ?><p class="lead reveal"><?= nl2br(esc($inv['opening_text'])) ?></p><?php endif ?>
    </section>

    <section class="sec center">
        <div class="couple">
            <div class="person reveal">
                <img src="<?= esc($img($inv['groom_photo'])) ?>" alt="<?= esc($inv['groom_name']) ?>" loading="lazy">
                <h3 class="script"><?= esc($inv['groom_name']) ?></h3>
                <p><?= esc($inv['groom_parents']) ?></p>
            </div>
            <div class="amp script">&amp;</div>
            <div class="person reveal">
                <img src="<?= esc($img($inv['bride_photo'])) ?>" alt="<?= esc($inv['bride_name']) ?>" loading="lazy">
                <h3 class="script"><?= esc($inv['bride_name']) ?></h3>
                <p><?= esc($inv['bride_parents']) ?></p>
            </div>
        </div>
    </section>

    <?php if ($inv['quote']): ?>
    <section class="sec center alt">
        <blockquote class="reveal">
            “<?= esc($inv['quote']) ?>”
            <?php if ($inv['quote_source']): ?><cite>— <?= esc($inv['quote_source']) ?></cite><?php endif ?>
        </blockquote>
    </section>
    <?php endif ?>

    <section class="sec center">
        <h2 class="title">Menuju Hari Bahagia</h2>
        <div id="countdown" class="countdown reveal" data-target="<?= date('c', $ts) ?>">
            <div><b data-u="d">0</b><span>Hari</span></div>
            <div><b data-u="h">0</b><span>Jam</span></div>
            <div><b data-u="m">0</b><span>Menit</span></div>
            <div><b data-u="s">0</b><span>Detik</span></div>
        </div>
        <?php if ($calendar): ?><a class="btn ghost" href="<?= esc($calendar) ?>" target="_blank" rel="noopener">Simpan ke Kalender</a><?php endif ?>
    </section>

    <?php if ($events): ?>
    <section class="sec center alt">
        <h2 class="title">Rangkaian Acara</h2>
        <div class="events">
            <?php foreach ($events as $ev): $et = strtotime($ev['event_at']); ?>
            <article class="card reveal">
                <h3 class="script"><?= esc($ev['title']) ?></h3>
                <p class="when"><?= esc($tgl($et)) ?></p>
                <p><?= date('H:i', $et) ?><?= $ev['end_time'] ? ' – ' . esc($ev['end_time']) : '' ?></p>
                <p class="venue"><?= esc($ev['venue']) ?></p>
                <p class="addr"><?= esc($ev['address']) ?></p>
                <?php if ($ev['map_url']): ?><a class="btn ghost" href="<?= esc($ev['map_url']) ?>" target="_blank" rel="noopener">Lihat Peta</a><?php endif ?>
            </article>
            <?php endforeach ?>
        </div>
    </section>
    <?php endif ?>

    <?php if ($inv['live_url']): ?>
    <section class="sec center">
        <h2 class="title">Live Streaming</h2>
        <p class="lead">Tidak bisa hadir langsung? Saksikan prosesi pernikahan kami secara online.</p>
        <?php if ($liveEmbed): ?>
            <div class="video reveal"><iframe src="<?= esc($liveEmbed) ?>" title="Live streaming" allow="accelerometer; encrypted-media; picture-in-picture; fullscreen" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe></div>
        <?php else: ?>
            <a class="btn" href="<?= esc($inv['live_url']) ?>" target="_blank" rel="noopener">Tonton Live</a>
        <?php endif ?>
    </section>
    <?php endif ?>

    <?php if ($gallery): ?>
    <section class="sec center">
        <h2 class="title">Galeri</h2>
        <div class="gallery">
            <?php foreach ($gallery as $g): ?>
                <a href="<?= esc($img($g['file'])) ?>" class="reveal"><img src="<?= esc($img($g['file'])) ?>" alt="Galeri" loading="lazy"></a>
            <?php endforeach ?>
        </div>
    </section>
    <?php endif ?>

    <?php if ($gifts || $inv['gift_address']): ?>
    <section class="sec center alt">
        <h2 class="title">Tanda Kasih</h2>
        <p class="lead">Doa restu Anda adalah hadiah terindah. Namun jika ingin memberi tanda kasih, dapat melalui:</p>
        <div class="events">
            <?php foreach ($gifts as $g): ?>
            <article class="card reveal">
                <h3><?= esc($g['bank']) ?></h3>
                <p class="acc"><?= esc($g['no']) ?></p>
                <p>a.n. <?= esc($g['name']) ?></p>
                <button type="button" class="btn ghost copy" data-copy="<?= esc($g['no']) ?>">Salin Nomor</button>
            </article>
            <?php endforeach ?>
        </div>
        <?php if ($inv['gift_address']): ?>
        <article class="card reveal gift-addr">
            <h3>Kirim Kado</h3>
            <p><?= nl2br(esc($inv['gift_address'])) ?></p>
            <button type="button" class="btn ghost copy" data-copy="<?= esc($inv['gift_address']) ?>">Salin Alamat</button>
        </article>
        <?php endif ?>
    </section>
    <?php endif ?>

    <section class="sec center" id="rsvp">
        <h2 class="title">Konfirmasi &amp; Ucapan</h2>

        <?php if (session()->getFlashdata('rsvp_ok')): ?><div class="flash ok"><?= esc(session()->getFlashdata('rsvp_ok')) ?></div><?php endif ?>
        <?php if (session()->getFlashdata('rsvp_error')): ?><div class="flash err"><?= esc(session()->getFlashdata('rsvp_error')) ?></div><?php endif ?>

        <form method="post" action="<?= site_url('u/' . $inv['slug'] . '/rsvp') ?>" class="rsvp">
            <?= csrf_field() ?>
            <input type="hidden" name="guest_code" value="<?= esc($guestCode ?? '') ?>">
            <input name="name" placeholder="Nama" maxlength="100" required value="<?= esc(old('name', $guestName)) ?>">
            <select name="attendance" required>
                <option value="hadir" <?= old('attendance') === 'hadir' ? 'selected' : '' ?>>Ya, saya akan hadir</option>
                <option value="ragu" <?= old('attendance') === 'ragu' ? 'selected' : '' ?>>Masih ragu</option>
                <option value="tidak" <?= old('attendance') === 'tidak' ? 'selected' : '' ?>>Maaf, tidak bisa hadir</option>
            </select>
            <?php if (count($events) > 1): ?>
            <fieldset class="evpick">
                <legend>Acara yang akan dihadiri</legend>
                <?php foreach ($events as $ev): ?>
                <label><input type="checkbox" name="events[]" value="<?= (int) $ev['id'] ?>" <?= in_array((string) $ev['id'], (array) old('events', array_map('strval', array_column($events, 'id')))) ? 'checked' : '' ?>> <?= esc($ev['title']) ?></label>
                <?php endforeach ?>
            </fieldset>
            <?php endif ?>
            <input type="number" name="pax" min="1" max="<?= (int) $quota ?>" value="<?= esc(old('pax', '1')) ?>" placeholder="Jumlah tamu (maks. <?= (int) $quota ?>)" aria-label="Jumlah tamu, maksimal <?= (int) $quota ?>">
            <textarea name="message" rows="4" maxlength="500" placeholder="Tulis ucapan dan doa..."><?= esc(old('message')) ?></textarea>
            <button class="btn" type="submit">Kirim</button>
        </form>

        <div class="wishes">
            <?php foreach ($wishes as $w): ?>
            <div class="wish">
                <strong><?= esc($w['name']) ?></strong>
                <small class="tag <?= esc($w['attendance']) ?>"><?= ['hadir' => 'Hadir', 'tidak' => 'Tidak hadir', 'ragu' => 'Ragu'][$w['attendance']] ?? '' ?></small>
                <p><?= nl2br(esc($w['message'])) ?></p>
                <small><?= esc(date('d/m/Y H:i', strtotime($w['created_at']))) ?></small>
            </div>
            <?php endforeach ?>
            <?php if (! $wishes): ?><p class="muted">Jadilah yang pertama mengirim ucapan.</p><?php endif ?>
        </div>
    </section>

    <footer class="sec center foot">
        <p class="lead">Merupakan kebahagiaan bagi kami apabila Anda berkenan hadir dan memberikan doa restu.</p>
        <p>Kami yang berbahagia,</p>
        <h2 class="script big"><?= esc($inv['groom_nick']) ?> &amp; <?= esc($inv['bride_nick']) ?></h2>
    </footer>
</main>

<script src="<?= base_url('assets/js/invitation.js') ?>"></script>
</body>
</html>
