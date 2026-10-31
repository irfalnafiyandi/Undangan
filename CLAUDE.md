# Undangan Digital (CodeIgniter 4)

Aplikasi undangan pernikahan multi-pasangan. Bahasa percakapan dan teks antarmuka: **Indonesia**.

## Perintah
- `php spark serve` — jalankan lokal (atau lewat Laragon: `http://undangan.test`)
- `php spark migrate` — terapkan migrasi; **jangan edit migrasi lama**, buat file migrasi baru
- `php spark db:seed DemoSeeder` — data contoh (hanya untuk database kosong)
- `composer install` — pasang dependensi setelah `git pull`

## Struktur
- `app/Controllers/Invitation.php` — halaman publik `/u/{slug}` dan RSVP
- `app/Controllers/Admin/` — panel admin (`Invitations`, `Manage`, `Users`, `Auth`)
- `app/Views/invitation/show.php` — satu view untuk semua template
- `app/Views/admin/`, `app/Views/layouts/admin.php` — panel admin
- `public/assets/css/invitation.css` — template = blok `body[data-theme=kunci]`
- `app/Models/InvitationModel.php` — daftar template di konstanta `TEMPLATES`

## Aturan
- Template baru: tambah entri di `InvitationModel::TEMPLATES` + blok CSS `body[data-theme=...]`. Jangan ubah view kecuali struktur bagian memang berbeda.
- Semua form POST wajib `csrf_field()`. Output ke HTML wajib `esc()`.
- Akses data undangan di admin lewat `ownOr404()` agar klien tidak melihat undangan orang lain.
- Jangan commit `.env`, `writable/*.sqlite`, `public/uploads/*`, atau dump database berisi data tamu asli.
- Gambar dan musik di `assets/img/dummy` dan `assets/audio` hanya contoh; ganti dengan aset berlisensi.
