# Undangan Digital (CodeIgniter 4)

Website undangan pernikahan **multi-pasangan**: satu aplikasi, banyak undangan, masing-masing dengan link sendiri (`/u/{slug}`).

## Fitur
- Undangan publik responsif (HP-first): cover dengan nama tamu, mempelai, kutipan, hitung mundur, acara + peta, galeri, amplop digital (salin rekening), RSVP & buku ucapan, musik latar, tombol "Simpan ke Kalender".
- Link personal per tamu: `/u/{slug}/{kode}` atau `/u/{slug}?to=Nama`; tombol kirim via WhatsApp.
- 5 template: Elegant Sage, Romantic Rose, Royal Navy (gelap), Modern Minimal, dan Adat Jawa. Dipilih per undangan di admin (kartu dengan tombol *Pratinjau*).
- Panel admin `/admin`: peran **admin** (semua undangan + kelola akun) dan **client** (hanya undangan miliknya).

## Menjalankan
```bash
composer install
cp env .env            # lalu atur baseURL / database bila perlu
php spark migrate
php spark db:seed DemoSeeder
php spark serve        # http://localhost:8080
```
Default database SQLite (`writable/database.sqlite`). Untuk MySQL, atur `database.default.*` di `.env`.

Akun demo (ganti sebelum produksi!): `admin@undangan.test` / `admin123`, `klien@undangan.test` / `klien123`.
Contoh undangan: `/u/rina-dan-adit`, `/u/dimas-dan-sinta`, `/u/fajar-dan-nadia`.

Produksi: set `CI_ENVIRONMENT = production`, arahkan document root ke folder `public/`, dan pastikan `public/uploads` dapat ditulis.

## Menambah / mengganti template
Template = satu blok CSS + satu entri daftar, tanpa mengubah view:
1. Tambah entri di `InvitationModel::TEMPLATES` (kunci, label, deskripsi, 3 warna swatch).
2. Tambah blok `body[data-theme=kunci-anda] { ... }` di `public/assets/css/invitation.css`. Atur variabel `--bg --alt --ink --accent --gold --card`, font (`--f-script --f-serif`), lalu override bentuk (cover, foto, kartu) sesuai selera. Lihat blok *Modern Minimal* sebagai contoh.
3. Jika memakai font baru, tambahkan di tag `<link>` Google Fonts pada `app/Views/invitation/show.php`.

Pratinjau tanpa menyimpan: buka `/u/{slug}?theme=kunci` saat sudah login admin.

## Aset dummy
Gambar di `public/assets/img/dummy/` adalah SVG placeholder. Musik `public/assets/audio/canon-demo.mp3` adalah aransemen sintetis sederhana progresi Canon in D (domain publik). Ganti lewat admin (kolom Musik) dengan lagu berlisensi yang Anda miliki.
