<?php
require __DIR__ . '/db.php';
session_name($config['session_name'] ?? 'portal_guru_admin');
session_start();

function h($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function csrf(){ if(empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(24)); return $_SESSION['csrf']; }
function checkCsrf(){ if(!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) exit('CSRF tidak valid'); }
function getSetting(PDO $pdo, string $key, string $default=''): string {
    $st = $pdo->prepare('SELECT setting_value FROM settings WHERE setting_key=? LIMIT 1');
    $st->execute([$key]);
    $v = $st->fetchColumn();
    return $v === false ? $default : (string)$v;
}
function setSetting(PDO $pdo, string $key, string $value): void {
    $st = $pdo->prepare('INSERT INTO settings(setting_key,setting_value) VALUES(?,?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)');
    $st->execute([$key,$value]);
}
function absoluteUrl(string $value): string {
    if ($value === '') return '';
    if (preg_match('~^https?://~i', $value)) return $value;
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $dir = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    return $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $dir . '/' . ltrim($value, '/');
}
function removeOldLocalPhoto(string $relative): void {
    if (!preg_match('~^uploads/profile-[a-zA-Z0-9._-]+$~', $relative)) return;
    $path = __DIR__ . '/' . $relative;
    if (is_file($path)) @unlink($path);
}

if (empty($_SESSION['admin_id'])) {
    header('Location: admin.php');
    exit;
}

$error = '';
$current = getSetting($pdo, 'profile_photo');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    checkCsrf();
    $action = $_POST['action'] ?? 'upload';

    if ($action === 'remove') {
        removeOldLocalPhoto($current);
        setSetting($pdo, 'profile_photo', '');
        header('Location: upload-photo.php?removed=1');
        exit;
    }

    if (!isset($_FILES['photo'])) {
        $error = 'File foto belum dipilih.';
    } elseif ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
        $messages = [
            UPLOAD_ERR_INI_SIZE => 'Ukuran file melebihi batas server.',
            UPLOAD_ERR_FORM_SIZE => 'Ukuran file melebihi batas formulir.',
            UPLOAD_ERR_PARTIAL => 'Upload hanya diterima sebagian.',
            UPLOAD_ERR_NO_FILE => 'File foto belum dipilih.',
            UPLOAD_ERR_NO_TMP_DIR => 'Folder sementara server tidak tersedia.',
            UPLOAD_ERR_CANT_WRITE => 'Server gagal menulis file.',
            UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh ekstensi PHP.'
        ];
        $error = $messages[$_FILES['photo']['error']] ?? 'Upload gagal.';
    } elseif ($_FILES['photo']['size'] > 5 * 1024 * 1024) {
        $error = 'Ukuran foto maksimal 5 MB.';
    } elseif (!is_uploaded_file($_FILES['photo']['tmp_name'])) {
        $error = 'File upload tidak valid.';
    } else {
        $info = @getimagesize($_FILES['photo']['tmp_name']);
        $mime = $info['mime'] ?? '';
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp'
        ];

        if (!$info || !isset($allowed[$mime])) {
            $error = 'Format foto harus JPG, PNG, atau WebP.';
        } else {
            $uploadDir = __DIR__ . '/uploads';
            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
                $error = 'Folder uploads tidak dapat dibuat.';
            } elseif (!is_writable($uploadDir)) {
                $error = 'Folder uploads tidak memiliki izin tulis.';
            } else {
                $ext = $allowed[$mime];
                $filename = 'profile-' . date('YmdHis') . '-' . bin2hex(random_bytes(6)) . '.' . $ext;
                $target = $uploadDir . '/' . $filename;

                if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
                    $error = 'Gagal menyimpan foto ke server.';
                } else {
                    @chmod($target, 0644);
                    $relative = 'uploads/' . $filename;
                    if ($current !== $relative) removeOldLocalPhoto($current);
                    setSetting($pdo, 'profile_photo', $relative);
                    header('Location: upload-photo.php?saved=1');
                    exit;
                }
            }
        }
    }
}

$current = getSetting($pdo, 'profile_photo');
$preview = absoluteUrl($current);
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Upload Foto Profil - Portal Guru</title>
<style>
:root{--p:#2563eb;--bg:#f5f7fb;--line:#e2e8f0;--text:#0f172a;--muted:#64748b;--danger:#b91c1c}*{box-sizing:border-box}body{margin:0;background:var(--bg);color:var(--text);font-family:Inter,system-ui,-apple-system,Segoe UI,sans-serif}.wrap{max-width:720px;margin:40px auto;padding:0 18px}.card{background:#fff;border:1px solid var(--line);border-radius:20px;padding:24px;box-shadow:0 16px 40px rgba(15,23,42,.06)}h1{margin:0 0 8px}.muted{color:var(--muted)}.preview{margin:22px 0;display:flex;justify-content:center}.preview img{width:220px;height:220px;object-fit:cover;border-radius:22px;border:1px solid var(--line);background:#eef2ff}.placeholder{width:220px;height:220px;border-radius:22px;border:1px dashed #94a3b8;display:flex;align-items:center;justify-content:center;color:var(--muted);background:#f8fafc}.field{margin:16px 0}label{display:block;font-weight:700;margin-bottom:7px}input[type=file]{width:100%;padding:12px;border:1px solid #cbd5e1;border-radius:12px;background:#fff}.hint{font-size:13px;color:var(--muted);margin-top:6px}.actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:18px}.btn{display:inline-flex;align-items:center;justify-content:center;padding:11px 15px;border:0;border-radius:11px;background:#e2e8f0;color:#0f172a;text-decoration:none;font-weight:750;cursor:pointer}.primary{background:var(--p);color:#fff}.danger{background:#fee2e2;color:var(--danger)}.ok,.err{padding:12px 14px;border-radius:12px;margin:16px 0}.ok{background:#ecfdf5;color:#047857}.err{background:#fef2f2;color:#b91c1c}.current{font-size:13px;word-break:break-all;background:#f8fafc;border:1px solid var(--line);padding:10px;border-radius:10px}@media(max-width:520px){.wrap{margin:18px auto}.card{padding:18px}.preview img,.placeholder{width:180px;height:180px}.actions .btn{width:100%}}
</style>
</head>
<body>
<div class="wrap">
  <div class="card">
    <h1>Upload Foto Profil</h1>
    <p class="muted">Foto yang diunggah akan otomatis digunakan pada kartu profil di landing page.</p>

    <?php if(isset($_GET['saved'])):?><div class="ok">Foto profil berhasil diunggah dan disimpan.</div><?php endif;?>
    <?php if(isset($_GET['removed'])):?><div class="ok">Foto profil berhasil dihapus.</div><?php endif;?>
    <?php if($error):?><div class="err"><?=h($error)?></div><?php endif;?>

    <div class="preview">
      <?php if($preview):?>
        <img src="<?=h($preview)?>?v=<?=time()?>" alt="Foto profil saat ini">
      <?php else:?>
        <div class="placeholder">Belum ada foto</div>
      <?php endif;?>
    </div>

    <?php if($current):?><div class="current"><strong>File aktif:</strong><br><?=h($current)?></div><?php endif;?>

    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf" value="<?=h(csrf())?>">
      <input type="hidden" name="action" value="upload">
      <div class="field">
        <label for="photo">Pilih foto</label>
        <input id="photo" type="file" name="photo" accept="image/jpeg,image/png,image/webp" required>
        <div class="hint">JPG, PNG, atau WebP. Maksimal 5 MB.</div>
      </div>
      <div class="actions">
        <button class="btn primary" type="submit">Upload & Gunakan Foto</button>
        <a class="btn" href="admin.php#profil">Kembali ke Admin</a>
      </div>
    </form>

    <?php if($current):?>
    <form method="post" onsubmit="return confirm('Hapus foto profil saat ini?')">
      <input type="hidden" name="csrf" value="<?=h(csrf())?>">
      <input type="hidden" name="action" value="remove">
      <div class="actions"><button class="btn danger" type="submit">Hapus Foto</button></div>
    </form>
    <?php endif;?>
  </div>
</div>
</body>
</html>
