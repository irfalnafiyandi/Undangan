<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\GalleryModel;
use App\Models\GuestModel;
use App\Models\InvitationModel;
use App\Models\RsvpModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Invitation extends BaseController
{
    private function findOr404(string $slug): array
    {
        $inv = (new InvitationModel())->where('slug', $slug)->first();
        if (! $inv || (! $inv['is_published'] && ! session()->get('user_id'))) {
            throw PageNotFoundException::forPageNotFound('Undangan tidak ditemukan.');
        }

        return $inv;
    }

    public function show(string $slug, ?string $code = null): string
    {
        $inv    = $this->findOr404($slug);
        $guests = new GuestModel();
        $guest  = null;

        if ($code !== null) {
            $guest = $guests->where(['invitation_id' => $inv['id'], 'code' => $code])->first();
            if ($guest && ! $guest['opened_at']) {
                $guests->update($guest['id'], ['opened_at' => date('Y-m-d H:i:s')]);
            }
        }

        $guestName = $guest['name'] ?? trim((string) $this->request->getGet('to'));

        $rsvps = new RsvpModel();

        return view('invitation/show', [
            'inv'       => $inv,
            'events'    => (new EventModel())->where('invitation_id', $inv['id'])->orderBy('event_at')->findAll(),
            'gallery'   => (new GalleryModel())->where('invitation_id', $inv['id'])->findAll(),
            'wishes'    => $rsvps->where('invitation_id', $inv['id'])->where("message <> ''")->orderBy('id', 'DESC')->findAll(50),
            'guestName' => mb_substr($guestName, 0, 100),
            'guestCode' => $guest['code'] ?? null,
            'gifts'     => $this->parseGifts((string) $inv['gifts']),
        ]);
    }

    public function rsvp(string $slug)
    {
        $inv = $this->findOr404($slug);

        $rules = [
            'name'       => 'required|max_length[100]',
            'attendance' => 'required|in_list[hadir,tidak,ragu]',
            'pax'        => 'permit_empty|integer|greater_than[0]|less_than[11]',
            'message'    => 'permit_empty|max_length[500]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->to("u/{$slug}#rsvp")->withInput()->with('rsvp_error', implode(' ', $this->validator->getErrors()));
        }

        $guest = null;
        if ($code = $this->request->getPost('guest_code')) {
            $guest = (new GuestModel())->where(['invitation_id' => $inv['id'], 'code' => $code])->first();
        }

        $attendance = $this->request->getPost('attendance');
        (new RsvpModel())->insert([
            'invitation_id' => $inv['id'],
            'guest_id'      => $guest['id'] ?? null,
            'name'          => trim($this->request->getPost('name')),
            'attendance'    => $attendance,
            'pax'           => $attendance === 'hadir' ? (int) ($this->request->getPost('pax') ?: 1) : 0,
            'message'       => trim((string) $this->request->getPost('message')),
        ]);

        $back = $guest ? "u/{$slug}/{$guest['code']}" : "u/{$slug}";

        return redirect()->to($back . '#rsvp')->with('rsvp_ok', 'Terima kasih, konfirmasi dan ucapan Anda sudah kami terima.');
    }

    /** Format per baris: "Bank|No Rekening|Atas Nama" */
    private function parseGifts(string $raw): array
    {
        $out = [];
        foreach (preg_split('/\R/', $raw) as $line) {
            $p = array_map('trim', explode('|', $line));
            if (count($p) >= 3 && $p[0] !== '') {
                $out[] = ['bank' => $p[0], 'no' => $p[1], 'name' => $p[2]];
            }
        }

        return $out;
    }
}
