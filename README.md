# Undangan Digital (CodeIgniter 4)

Website undangan pernikahan **multi-pasangan**: satu aplikasi, banyak undangan, masing-masing dengan link sendiri (`/u/{slug}`).

## Fitur
- Undangan publik responsif (HP-first): cover dengan nama tamu, mempelai, kutipan, hitung mundur, acara + peta, galeri, amplop digital (salin rekening), RSVP & buku ucapan, musik latar, tombol "Simpan ke Kalender".
- Link personal per tamu: `/u/{slug}/{kode}` atau `/u/{slug}?to=Nama`; tombol kirim via WhatsApp.
- 3 tema warna: Sage, Rose, Navy (mudah ditambah di `public/assets/css/invitation.css`).
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

## Aset dummy
Gambar di `public/assets/img/dummy/` adalah SVG placeholder. Musik `public/assets/audio/canon-demo.mp3` adalah aransemen sintetis sederhana progresi Canon in D (domain publik). Ganti lewat admin (kolom Musik) dengan lagu berlisensi yang Anda miliki.
