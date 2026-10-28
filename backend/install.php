<?php
require __DIR__ . '/db.php';

$pdo->exec("CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$pdo->exec("CREATE TABLE IF NOT EXISTS settings (
  setting_key VARCHAR(100) PRIMARY KEY,
  setting_value TEXT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$pdo->exec("CREATE TABLE IF NOT EXISTS materials (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(150) NOT NULL UNIQUE,
  class_name VARCHAR(80) NOT NULL,
  mapel VARCHAR(120) NOT NULL,
  tp VARCHAR(50) NOT NULL,
  title VARCHAR(200) NOT NULL,
  summary TEXT NULL,
  content LONGTEXT NULL,
  tags TEXT NULL,
  resource_url TEXT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$pdo->exec("CREATE TABLE IF NOT EXISTS modules (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  class_name VARCHAR(80) NOT NULL,
  type VARCHAR(50) NOT NULL,
  description TEXT NULL,
  file_url TEXT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$pdo->exec("CREATE TABLE IF NOT EXISTS portfolio (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  student VARCHAR(180) NULL,
  class_name VARCHAR(80) NOT NULL,
  description TEXT NULL,
  image_url TEXT NULL,
  demo_url TEXT NULL,
  repo_url TEXT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$defaults = [
  'profile_name' => 'Wendhie Tri',
  'profile_title' => 'Guru Produktif RPL & PTGM',
  'profile_school' => 'SMK Bhakti Persada Kendal',
  'profile_location' => 'Kendal, Jawa Tengah',
  'profile_bio' => 'Portal pembelajaran personal untuk materi, modul, LKPD, proyek siswa, serta layanan pembelajaran digital.',
  'profile_email' => 'guru@sekolah.sch.id',
  'profile_whatsapp' => 'https://wa.me/6200000000000',
  'profile_photo' => '',
  'profile_logo' => '',
  'link_sibela' => '',
  'link_classroom' => 'https://classroom.google.com/',
  'link_drive' => 'https://drive.google.com/',
  'link_github' => 'https://github.com/',
  'link_school' => ''
];
$stmt = $pdo->prepare("INSERT IGNORE INTO settings(setting_key,setting_value) VALUES(?,?)");
foreach ($defaults as $k => $v) $stmt->execute([$k,$v]);

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? 'admin');
    $password = $_POST['password'] ?? '';
    if (strlen($username) < 3 || strlen($password) < 8) {
        $message = 'Username minimal 3 karakter dan password minimal 8 karakter.';
    } else {
        $count = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        if ($count > 0) {
            $message = 'Akun admin sudah tersedia. Demi keamanan, hapus atau rename install.php setelah instalasi.';
        } else {
            $st = $pdo->prepare("INSERT INTO users(username,password_hash) VALUES(?,?)");
            $st->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
            $message = 'Instalasi berhasil. Silakan login melalui admin.php. Hapus atau rename install.php setelah selesai.';
        }
    }
}
?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Instalasi Portal Guru</title><style>body{font-family:system-ui;background:#f5f7fb;margin:0;color:#0f172a}.box{max-width:540px;margin:7vh auto;background:#fff;padding:28px;border-radius:18px;border:1px solid #e2e8f0;box-shadow:0 18px 50px rgba(15,23,42,.08)}input{width:100%;padding:12px;border:1px solid #cbd5e1;border-radius:10px;margin:6px 0 14px;box-sizing:border-box}button{width:100%;padding:12px;border:0;border-radius:10px;background:#2563eb;color:#fff;font-weight:700}.msg{padding:12px;background:#eff6ff;border-radius:10px;margin-bottom:14px}</style></head><body><div class="box"><h1>Instalasi Backend Portal Guru</h1><p>Database dan tabel telah diperiksa. Buat akun administrator pertama.</p><?php if($message):?><div class="msg"><?=htmlspecialchars($message)?></div><?php endif;?><form method="post"><label>Username</label><input name="username" value="admin" required><label>Password</label><input type="password" name="password" minlength="8" required><button>Buat Administrator</button></form></div></body></html>
