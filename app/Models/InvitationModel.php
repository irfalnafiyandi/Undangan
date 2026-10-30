<?php

namespace App\Models;

use CodeIgniter\Model;

class InvitationModel extends Model
{
    protected $table         = 'invitations';
    protected $allowedFields = [
        'user_id', 'slug', 'theme', 'groom_name', 'groom_nick', 'groom_parents', 'groom_photo',
        'bride_name', 'bride_nick', 'bride_parents', 'bride_photo', 'cover_photo', 'wedding_date',
        'opening_text', 'quote', 'quote_source', 'music_url', 'gifts', 'is_published',
    ];
    protected $useTimestamps = true;

    /**
     * Template undangan. Kunci = nilai kolom `theme` = atribut data-theme di CSS.
     * Untuk menambah template: tambah entri di sini + blok body[data-theme=...] di invitation.css.
     * colors: [latar, aksen, emas/penekanan] hanya untuk swatch pratinjau di admin.
     */
    public const TEMPLATES = [
        'sage'     => ['label' => 'Elegant Sage', 'desc' => 'Hijau lembut, cover melengkung, klasik & tenang', 'colors' => ['#faf8f2', '#7f9b84', '#b8985a']],
        'rose'     => ['label' => 'Romantic Rose', 'desc' => 'Pink blush yang hangat dan romantis', 'colors' => ['#fdf6f5', '#c98e93', '#b8895a']],
        'navy'     => ['label' => 'Royal Navy', 'desc' => 'Mode gelap biru malam dengan aksen emas, mewah', 'colors' => ['#14213d', '#e3c26b', '#c4a24d']],
        'minimal'  => ['label' => 'Modern Minimal', 'desc' => 'Putih bersih, hitam tegas, tipografi modern', 'colors' => ['#ffffff', '#111111', '#8a8a8a']],
        'javanese' => ['label' => 'Adat Jawa', 'desc' => 'Cokelat sogan dan emas dengan motif batik', 'colors' => ['#f3e6cf', '#6b3f1d', '#b8862b']],
    ];

    /** Undangan milik user; admin melihat semuanya. */
    public function forUser(int $id, string $role, int $userId): ?array
    {
        $row = $this->find($id);
        if (! $row || ($role !== 'admin' && (int) $row['user_id'] !== $userId)) {
            return null;
        }

        return $row;
    }
}
