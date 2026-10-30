<?php

namespace App\Controllers\Admin;

use App\Models\EventModel;
use App\Models\GalleryModel;
use App\Models\GuestModel;
use App\Models\RsvpModel;

/** Sub-resource undangan: acara, galeri, tamu, RSVP. */
class Manage extends Invitations
{
    private function back(int $id, string $msg, string $anchor)
    {
        return redirect()->to("/admin/invitations/{$id}#{$anchor}")->with('ok', $msg);
    }

    public function addEvent(int $id)
    {
        $this->authorize($id);
        $rules = [
            'title'    => 'required|max_length[100]',
            'event_at' => 'required|valid_date[Y-m-d\TH:i]',
            'venue'    => 'required|max_length[150]',
            'map_url'  => 'permit_empty|valid_url_strict|max_length[500]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->to("/admin/invitations/{$id}#acara")->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $p = $this->request->getPost();
        (new EventModel())->insert([
            'invitation_id' => $id,
            'title'         => trim($p['title']),
            'event_at'      => str_replace('T', ' ', $p['event_at']) . ':00',
            'end_time'      => trim((string) ($p['end_time'] ?? '')),
            'venue'         => trim($p['venue']),
            'address'       => trim((string) ($p['address'] ?? '')),
            'map_url'       => trim((string) ($p['map_url'] ?? '')),
        ]);

        return $this->back($id, 'Acara ditambahkan.', 'acara');
    }

    public function deleteEvent(int $id, int $eventId)
    {
        $this->authorize($id);
        (new EventModel())->where(['id' => $eventId, 'invitation_id' => $id])->delete();

        return $this->back($id, 'Acara dihapus.', 'acara');
    }

    public function addGallery(int $id)
    {
        $this->authorize($id);
        $count = 0;
        foreach ($this->request->getFileMultiple('photos') ?: [] as $file) {
            if (! $file->isValid() || $file->hasMoved() || ! str_starts_with((string) $file->getMimeType(), 'image/') || $file->getSize() > 4 * 1024 * 1024) {
                continue;
            }
            $name = $file->getRandomName();
            $file->move(FCPATH . "uploads/{$id}", $name);
            (new GalleryModel())->insert(['invitation_id' => $id, 'file' => "uploads/{$id}/{$name}"]);
            $count++;
        }

        return $this->back($id, "{$count} foto diunggah.", 'galeri');
    }

    public function deleteGallery(int $id, int $photoId)
    {
        $this->authorize($id);
        $model = new GalleryModel();
        $row   = $model->where(['id' => $photoId, 'invitation_id' => $id])->first();
        if ($row) {
            if (str_starts_with($row['file'], "uploads/{$id}/")) {
                @unlink(FCPATH . $row['file']);
            }
            $model->delete($photoId);
        }

        return $this->back($id, 'Foto dihapus.', 'galeri');
    }

    /** Satu tamu per baris: "Nama" atau "Nama|08123456789". */
    public function addGuests(int $id)
    {
        $this->authorize($id);
        $model = new GuestModel();
        $n     = 0;
        foreach (preg_split('/\R/', (string) $this->request->getPost('guests')) as $line) {
            [$name, $phone] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');
            if ($name === '') {
                continue;
            }
            $model->insert([
                'invitation_id' => $id,
                'name'          => mb_substr($name, 0, 100),
                'phone'         => preg_replace('/\D/', '', $phone),
                'code'          => bin2hex(random_bytes(4)),
            ]);
            $n++;
        }

        return $this->back($id, "{$n} tamu ditambahkan.", 'tamu');
    }

    public function deleteGuest(int $id, int $guestId)
    {
        $this->authorize($id);
        (new GuestModel())->where(['id' => $guestId, 'invitation_id' => $id])->delete();

        return $this->back($id, 'Tamu dihapus.', 'tamu');
    }

    public function deleteRsvp(int $id, int $rsvpId)
    {
        $this->authorize($id);
        (new RsvpModel())->where(['id' => $rsvpId, 'invitation_id' => $id])->delete();

        return $this->back($id, 'Data dihapus.', 'rsvp');
    }

    private function authorize(int $id): void
    {
        $this->ownOr404($id); // melempar 404 jika bukan milik user
    }
}
