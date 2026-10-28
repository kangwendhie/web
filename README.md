# Website Personal Guru - GitHub Pages

Website statis ini dirancang untuk profil personal guru dan dapat langsung diterbitkan melalui GitHub Pages.

## Struktur

- `index.html` - halaman utama.
- `styles.css` - desain dan responsivitas.
- `script.js` - menu, tema gelap, filter materi, dan pengisian profil.
- `config.js` - pusat pengaturan nama, sekolah, foto, dan semua tautan utama.
- `assets/` - foto guru dan logo sekolah.
- `downloads/` - modul dan LKPD contoh.

## 1. Ganti data profil

Buka `config.js`, lalu ubah:

- `name`
- `title`
- `school`
- `location`
- `bio`
- `email`
- `whatsapp`

## 2. Ganti foto guru

Cara paling mudah:

1. Simpan foto di folder `assets`, misalnya `foto-guru.jpg`.
2. Ubah `photo` pada `config.js` menjadi `assets/foto-guru.jpg`.

## 3. Ganti logo sekolah

1. Simpan logo resmi di folder `assets`, misalnya `logo-sekolah.png`.
2. Ubah `schoolLogo` pada `config.js` menjadi `assets/logo-sekolah.png`.

File `logo-sekolah.svg` yang tersedia hanya placeholder, bukan logo resmi sekolah.

## 4. Atur tautan SIBELA dan layanan lain

Pada `config.js`, ubah bagian `links`:

```js
links: {
  sibela: "https://alamat-sibela-anda",
  classroom: "https://classroom.google.com/...",
  drive: "https://drive.google.com/...",
  github: "https://github.com/username",
  schoolWebsite: "https://alamat-sekolah.sch.id"
}
```

## 5. Tambahkan PDF atau DOCX modul/LKPD

Letakkan file di folder `downloads`. Setelah itu ubah `href` pada bagian materi atau download center di `index.html`.

Contoh:

```html
<a href="downloads/Modul_HTML5.pdf" download>Unduh Modul</a>
```

## 6. Deploy ke GitHub Pages

1. Buat repository baru, misalnya `website-guru`.
2. Upload seluruh isi folder ini ke repository.
3. Buka **Settings > Pages**.
4. Pada **Source**, pilih **Deploy from a branch**.
5. Pilih branch **main** dan folder **/root**.
6. Simpan pengaturan.

Alamat website biasanya:

`https://USERNAME.github.io/website-guru/`

## Catatan

Website ini tidak membutuhkan PHP, MySQL, Node.js, atau framework. Seluruh fitur utama berjalan langsung di browser dan cocok untuk GitHub Pages.
