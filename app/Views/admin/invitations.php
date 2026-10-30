<?= $this->extend('layouts/admin') ?>
<?= $this->section('title') ?>Undangan<?= $this->endSection() ?>
<?= $this->section('content') ?>
<div class="row"><h1>Daftar Undangan</h1><a class="btn" href="<?= site_url('admin/invitations/new') ?>">+ Undangan Baru</a></div>
<div class="panel">
<table>
    <tr><th>Pasangan</th><th>Tanggal</th><th>Tema</th><th>RSVP</th><th>Status</th><th></th></tr>
    <?php foreach ($rows as $r): ?>
    <tr>
        <td><strong><?= esc($r['groom_nick']) ?> &amp; <?= esc($r['bride_nick']) ?></strong><br><small>/u/<?= esc($r['slug']) ?></small></td>
        <td><?= esc(date('d/m/Y H:i', strtotime($r['wedding_date']))) ?></td>
        <td><?= esc(\App\Models\InvitationModel::THEMES[$r['theme']] ?? $r['theme']) ?></td>
        <td><?= $r['rsvp_count'] ?></td>
        <td><?= $r['is_published'] ? 'Tayang' : 'Draft' ?></td>
        <td class="act">
            <a href="<?= site_url('u/' . $r['slug']) ?>" target="_blank">Lihat</a>
            <a href="<?= site_url('admin/invitations/' . $r['id']) ?>">Kelola</a>
        </td>
    </tr>
    <?php endforeach ?>
    <?php if (! $rows): ?><tr><td colspan="6" class="muted">Belum ada undangan.</td></tr><?php endif ?>
</table>
</div>
<?= $this->endSection() ?>
