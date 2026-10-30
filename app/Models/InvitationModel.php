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

    public const THEMES = ['sage' => 'Sage Green', 'rose' => 'Rose Blush', 'navy' => 'Navy Gold'];

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
