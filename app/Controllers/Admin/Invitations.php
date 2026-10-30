<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EventModel;
use App\Models\GalleryModel;
use App\Models\GuestModel;
use App\Models\InvitationModel;
use App\Models\RsvpModel;
use App\Models\UserModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Invitations extends BaseController
{
    protected function isAdmin(): bool
    {
        return session()->get('user_role') === 'admin';
    }

    protected function ownOr404(int $id): array
    {
        $inv = (new InvitationModel())->forUser($id, (string) session()->get('user_role'), (int) session()->get('user_id'));
        if (! $inv) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $inv;
    }

    public function index(): string
    {
        $model = new InvitationModel();
        if (! $this->isAdmin()) {
            $model->where('user_id', session()->get('user_id'));
        }
        $rows = $model->orderBy('id', 'DESC')->findAll();

        $rsvp = new RsvpModel();
        foreach ($rows as &$r) {
            $r['rsvp_count'] = $rsvp->where('invitation_id', $r['id'])->countAllResults();
        }

        return view('admin/invitations', ['rows' => $rows]);
    }

    public function new(): string
    {
        return view('admin/invitation_form', [
            'inv'    => null,
            'owners' => $this->isAdmin() ? (new UserModel())->findAll() : [],
        ]);
    }

    public function create()
    {
        $data = $this->collect();
        if ($data === null) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $data['user_id'] = $this->isAdmin() && $this->request->getPost('user_id')
            ? (int) $this->request->getPost('user_id')
            : (int) session()->get('user_id');
        $data['slug'] = $this->uniqueSlug((string) ($this->request->getPost('slug') ?: $data['groom_nick'] . ' dan ' . $data['bride_nick']));
        $this->applyUploads($data, 'misc');

        $model = new InvitationModel();
        $model->insert($data);

        return redirect()->to('/admin/invitations/' . $model->getInsertID())->with('ok', 'Undangan dibuat. Lengkapi acara, galeri dan daftar tamu di bawah.');
    }

    public function edit(int $id): string
    {
        $inv = $this->ownOr404($id);

        return view('admin/invitation_form', [
            'inv'     => $inv,
            'owners'  => $this->isAdmin() ? (new UserModel())->findAll() : [],
            'events'  => (new EventModel())->where('invitation_id', $id)->orderBy('event_at')->findAll(),
            'gallery' => (new GalleryModel())->where('invitation_id', $id)->findAll(),
            'guests'  => (new GuestModel())->where('invitation_id', $id)->orderBy('id', 'DESC')->findAll(),
            'rsvps'   => (new RsvpModel())->where('invitation_id', $id)->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function update(int $id)
    {
        $inv  = $this->ownOr404($id);
        $data = $this->collect();
        if ($data === null) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        if ($slug = trim((string) $this->request->getPost('slug'))) {
            $slug = url_title($slug, '-', true);
            if ($slug !== $inv['slug']) {
                $data['slug'] = $this->uniqueSlug($slug);
            }
        }
        if ($this->isAdmin() && $this->request->getPost('user_id')) {
            $data['user_id'] = (int) $this->request->getPost('user_id');
        }
        $this->applyUploads($data, (string) $id);

        (new InvitationModel())->update($id, $data);

        return redirect()->to("/admin/invitations/{$id}")->with('ok', 'Perubahan disimpan.');
    }

    public function delete(int $id)
    {
        $this->ownOr404($id);
        foreach ([new EventModel(), new GalleryModel(), new GuestModel(), new RsvpModel()] as $m) {
            $m->where('invitation_id', $id)->delete();
        }
        (new InvitationModel())->delete($id);

        return redirect()->to('/admin')->with('ok', 'Undangan dihapus.');
    }

    private function collect(): ?array
    {
        $rules = [
            'groom_name'   => 'required|max_length[100]',
            'groom_nick'   => 'required|max_length[50]',
            'bride_name'   => 'required|max_length[100]',
            'bride_nick'   => 'required|max_length[50]',
            'wedding_date' => 'required|valid_date[Y-m-d\TH:i]',
            'theme'        => 'required|in_list[' . implode(',', array_keys(InvitationModel::TEMPLATES)) . ']',
            'music_url'    => 'permit_empty|max_length[255]',
            'live_url'     => 'permit_empty|valid_url_strict|max_length[255]',
            'groom_photo_file' => 'permit_empty|is_image[groom_photo_file]|max_size[groom_photo_file,4096]',
            'bride_photo_file' => 'permit_empty|is_image[bride_photo_file]|max_size[bride_photo_file,4096]',
            'cover_photo_file' => 'permit_empty|is_image[cover_photo_file]|max_size[cover_photo_file,4096]',
        ];
        if (! $this->validate($rules)) {
            return null;
        }

        $p    = $this->request->getPost();
        $data = [];
        foreach (['groom_name', 'groom_nick', 'groom_parents', 'bride_name', 'bride_nick', 'bride_parents', 'opening_text', 'quote', 'quote_source', 'music_url', 'live_url', 'gift_address', 'gifts', 'theme'] as $f) {
            $data[$f] = trim((string) ($p[$f] ?? ''));
        }
        $data['wedding_date'] = str_replace('T', ' ', $p['wedding_date']) . ':00';
        $data['is_published'] = empty($p['is_published']) ? 0 : 1;

        return $data;
    }

    private function applyUploads(array &$data, string $folder): void
    {
        foreach (['groom_photo', 'bride_photo', 'cover_photo'] as $f) {
            $file = $this->request->getFile($f . '_file');
            if ($file && $file->isValid() && ! $file->hasMoved()) {
                $name = $file->getRandomName();
                $file->move(FCPATH . 'uploads/' . $folder, $name);
                $data[$f] = "uploads/{$folder}/{$name}";
            }
        }
    }

    private function uniqueSlug(string $base): string
    {
        $slug  = url_title($base, '-', true) ?: 'undangan';
        $model = new InvitationModel();
        $try   = $slug;
        while ($model->where('slug', $try)->first()) {
            $try = $slug . '-' . bin2hex(random_bytes(2));
        }

        return $try;
    }
}
