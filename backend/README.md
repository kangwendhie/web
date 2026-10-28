# Backend Portal Guru - PHP 8.3 + MySQL

Backend ini dipakai untuk mengelola landing page yang tetap di-host pada GitHub Pages.

## Arsitektur

- Frontend publik: GitHub Pages
- Backend admin: PHP 8.3 pada aaPanel / hosting PHP
- Database: MySQL 8 / MariaDB 10.4+
- API publik: `api.php?action=public`
- Dashboard admin: `admin.php`

## Instalasi di aaPanel

1. Buat subdomain, misalnya `admin.domainanda.com`.
2. Gunakan PHP 8.3.
3. Buat database MySQL, misalnya `portal_guru`.
4. Upload seluruh isi folder `backend` ke document root subdomain.
5. Salin `config.example.php` menjadi `config.php`.
6. Edit `config.php` dan isi host, nama database, username, password, serta `frontend_origin`.
7. Buka `https://admin.domainanda.com/install.php`.
8. Buat akun administrator pertama dengan password minimal 8 karakter.
9. Setelah berhasil, hapus atau rename `install.php`.
10. Login melalui `https://admin.domainanda.com/admin.php`.

## Menghubungkan GitHub Pages

Edit `config.js` pada frontend:

```js
window.PORTAL_API_URL = "https://admin.domainanda.com";
```

Lalu commit perubahan ke branch yang dipakai GitHub Pages.

## CORS

Nilai `frontend_origin` harus berupa origin GitHub Pages tanpa path repository.

Contoh:

```php
'frontend_origin' => 'https://kangwendhie.github.io',
```

## Menu Admin

Dashboard mendukung:

- Edit profil guru
- Edit nama sekolah, email, WhatsApp, foto, dan logo
- Edit link SIBELA, Google Classroom, Google Drive, GitHub, dan website sekolah
- Tambah, edit, hapus, aktifkan, atau nonaktifkan materi/TP
- Tambah, edit, hapus modul dan LKPD
- Tambah, edit, hapus portofolio karya siswa
- Pengaturan urutan tampil

## Keamanan

- Password disimpan menggunakan `password_hash()`.
- Login menggunakan PHP session.
- Form admin dilindungi CSRF token.
- Query database memakai prepared statement untuk data input.
- Jangan commit `config.php` yang berisi password database ke repository publik.
- Gunakan HTTPS untuk backend.
- Hapus atau rename `install.php` setelah instalasi.

## File penting

- `config.example.php` template konfigurasi
- `db.php` koneksi PDO
- `install.php` installer tabel dan admin pertama
- `api.php` API publik untuk landing page
- `admin.php` dashboard pengelolaan konten
