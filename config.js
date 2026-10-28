window.PORTAL_DEFAULT_DATA = {
  profile: {
    name: "Wendhie Tri",
    title: "Guru Produktif RPL & PTGM",
    school: "SMK Bhakti Persada Kendal",
    location: "Kendal, Jawa Tengah",
    bio: "Portal pembelajaran personal untuk materi, modul, LKPD, proyek siswa, serta akses cepat ke layanan pembelajaran digital.",
    photo: "assets/foto-guru.svg",
    schoolLogo: "assets/logo-sekolah.svg",
    email: "guru@sekolah.sch.id",
    whatsapp: "https://wa.me/6200000000000"
  },
  links: {
    sibela: "#",
    classroom: "https://classroom.google.com/",
    drive: "https://drive.google.com/",
    github: "https://github.com/",
    schoolWebsite: "#"
  },
  materials: [
    {
      id: "xi-rpl-tp-1-1",
      className: "XI RPL",
      mapel: "Pemrograman Web",
      tp: "TP 1.1",
      title: "Struktur Dasar HTML5",
      summary: "Memahami struktur dokumen HTML5 dan menerapkannya pada halaman web sederhana.",
      content: "Peserta didik mempelajari doctype, elemen html, head, body, heading, paragraf, tautan, gambar, daftar, dan struktur semantik dasar. Kegiatan diakhiri dengan proyek halaman profil sederhana.",
      tags: ["HTML5", "Struktur Web", "Dasar"],
      resourceUrl: "downloads/Modul_XI_RPL_HTML5.md"
    },
    {
      id: "xi-rpl-tp-1-2",
      className: "XI RPL",
      mapel: "Pemrograman Web",
      tp: "TP 1.2",
      title: "Elemen Semantik dan Navigasi",
      summary: "Menyusun halaman web yang terstruktur dengan elemen semantik dan navigasi yang konsisten.",
      content: "Fokus pembelajaran pada penggunaan header, nav, main, section, article, aside, dan footer. Peserta didik membangun situs informasi sekolah sederhana dengan navigasi antarbagiannya.",
      tags: ["HTML5", "Semantic", "Navigation"],
      resourceUrl: "downloads/Modul_XI_RPL_HTML5.md"
    },
    {
      id: "xi-rpl-tp-1-3",
      className: "XI RPL",
      mapel: "Pemrograman Web",
      tp: "TP 1.3",
      title: "Formulir HTML",
      summary: "Mendesain formulir web yang terstruktur, mudah digunakan, dan sesuai kebutuhan pengguna.",
      content: "Peserta didik mempelajari form, label, input, select, textarea, button, validasi HTML, dan prinsip aksesibilitas dasar pada formulir.",
      tags: ["Form", "Input", "UX"],
      resourceUrl: "downloads/LKPD_XI_RPL_Project_Web.md"
    },
    {
      id: "xii-ptgm-tp-1-1",
      className: "XII PTGM",
      mapel: "PTGM",
      tp: "TP 1.1",
      title: "Objek Multimedia Digital",
      summary: "Menganalisis penggunaan gambar, audio, dan video dalam karya multimedia digital.",
      content: "Pembelajaran membahas karakteristik objek multimedia, format file, resolusi, kompresi, hak cipta, dan pemilihan media sesuai konteks komunikasi.",
      tags: ["Multimedia", "Gambar", "Audio", "Video"],
      resourceUrl: "downloads/Modul_XII_PTGM_Multimedia.md"
    },
    {
      id: "xii-ptgm-tp-1-2",
      className: "XII PTGM",
      mapel: "PTGM",
      tp: "TP 1.2",
      title: "Literasi Digital Kreatif dan Etis",
      summary: "Menghasilkan karya digital kreatif dengan memperhatikan etika, keamanan, dan validitas informasi.",
      content: "Peserta didik menilai kredibilitas sumber, memahami lisensi, menghindari plagiarisme, menyaring konten negatif, serta membuat karya digital yang bertanggung jawab.",
      tags: ["Literasi Digital", "Etika", "Kreativitas"],
      resourceUrl: "downloads/LKPD_XII_PTGM_Literasi_Digital.md"
    },
    {
      id: "xii-ptgm-tp-1-9",
      className: "XII PTGM",
      mapel: "PTGM",
      tp: "TP 1.9",
      title: "Proyek Multimedia Terintegrasi",
      summary: "Mengembangkan proyek multimedia terintegrasi menggunakan pendekatan Project Based Learning.",
      content: "Siswa merancang ide, membagi peran tim, membuat aset, mengintegrasikan teks, grafis, audio, dan video, melakukan pengujian, lalu mempresentasikan hasil proyek.",
      tags: ["PjBL", "Project", "Multimedia"],
      resourceUrl: "#"
    }
  ],
  modules: [
    { id: "mod-xi-html", title: "Modul HTML5 Dasar", className: "XI RPL", type: "Modul", description: "Materi struktur dasar HTML5, elemen semantik, dan latihan mandiri.", fileUrl: "downloads/Modul_XI_RPL_HTML5.md" },
    { id: "lkpd-xi-web", title: "LKPD Proyek Website", className: "XI RPL", type: "LKPD", description: "LKPD proyek membuat website informasi sederhana menggunakan struktur HTML yang benar.", fileUrl: "downloads/LKPD_XI_RPL_Project_Web.md" },
    { id: "mod-xii-multi", title: "Modul Objek Multimedia", className: "XII PTGM", type: "Modul", description: "Materi gambar, audio, video, format file, kualitas, kompresi, dan hak cipta.", fileUrl: "downloads/Modul_XII_PTGM_Multimedia.md" },
    { id: "lkpd-xii-litdig", title: "LKPD Literasi Digital", className: "XII PTGM", type: "LKPD", description: "Aktivitas analisis konten digital, etika, validitas informasi, dan refleksi.", fileUrl: "downloads/LKPD_XII_PTGM_Literasi_Digital.md" }
  ],
  portfolio: [
    { id: "porto-1", title: "Website Wisata Lokal", student: "Tim XI RPL A", className: "XI RPL", description: "Website informasi destinasi wisata lokal dengan struktur semantik HTML5.", image: "assets/portfolio-1.svg", demoUrl: "#", repoUrl: "#" },
    { id: "porto-2", title: "Portal Profil Sekolah", student: "Tim XI RPL B", className: "XI RPL", description: "Prototype portal sekolah responsif dengan navigasi, profil, dan informasi kegiatan.", image: "assets/portfolio-2.svg", demoUrl: "#", repoUrl: "#" },
    { id: "porto-3", title: "Kampanye Literasi Digital", student: "Tim XII PTGM A", className: "XII PTGM", description: "Konten multimedia untuk kampanye penggunaan media digital secara sehat dan etis.", image: "assets/portfolio-3.svg", demoUrl: "#", repoUrl: "#" },
    { id: "porto-4", title: "Video Profil Kompetensi Keahlian", student: "Tim XII PTGM B", className: "XII PTGM", description: "Video profil yang memadukan teks, grafis, audio, dan video.", image: "assets/portfolio-4.svg", demoUrl: "#", repoUrl: "#" }
  ]
};
