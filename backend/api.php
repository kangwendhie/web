<?php
require __DIR__ . '/db.php';

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed = rtrim($config['frontend_origin'] ?? '', '/');
if ($allowed && $origin === $allowed) {
    header('Access-Control-Allow-Origin: ' . $allowed);
    header('Vary: Origin');
}
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Accept, Content-Type');
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;

function settingsMap(PDO $pdo): array {
    $rows = $pdo->query('SELECT setting_key,setting_value FROM settings')->fetchAll();
    $out = [];
    foreach ($rows as $r) $out[$r['setting_key']] = $r['setting_value'];
    return $out;
}
function absoluteUrl(string $value): string {
    if ($value === '') return '';
    if (preg_match('~^https?://~i', $value)) return $value;
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $dir = rtrim(str_replace('\\','/',dirname($_SERVER['SCRIPT_NAME'])), '/');
    return $scheme . '://' . $_SERVER['HTTP_HOST'] . $dir . '/' . ltrim($value,'/');
}

try {
    $s = settingsMap($pdo);
    $materials = $pdo->query("SELECT slug AS id,class_name AS className,mapel,tp,title,summary,content,tags,resource_url AS resourceUrl FROM materials WHERE is_active=1 ORDER BY sort_order,id")->fetchAll();
    foreach ($materials as &$m) {
        $m['tags'] = array_values(array_filter(array_map('trim', explode(',', $m['tags'] ?? ''))));
        if (!empty($m['resourceUrl'])) $m['resourceUrl'] = absoluteUrl($m['resourceUrl']);
    }
    unset($m);
    $modules = $pdo->query("SELECT CONCAT('module-',id) AS id,title,class_name AS className,type,description,file_url AS fileUrl FROM modules WHERE is_active=1 ORDER BY sort_order,id")->fetchAll();
    foreach ($modules as &$m) if (!empty($m['fileUrl'])) $m['fileUrl'] = absoluteUrl($m['fileUrl']);
    unset($m);
    $portfolio = $pdo->query("SELECT CONCAT('portfolio-',id) AS id,title,student,class_name AS className,description,image_url AS image,demo_url AS demoUrl,repo_url AS repoUrl FROM portfolio WHERE is_active=1 ORDER BY sort_order,id")->fetchAll();
    foreach ($portfolio as &$p) if (!empty($p['image'])) $p['image'] = absoluteUrl($p['image']);
    unset($p);

    echo json_encode(['data'=>[
        'profile'=>[
            'name'=>$s['profile_name'] ?? '',
            'title'=>$s['profile_title'] ?? '',
            'school'=>$s['profile_school'] ?? '',
            'location'=>$s['profile_location'] ?? '',
            'bio'=>$s['profile_bio'] ?? '',
            'email'=>$s['profile_email'] ?? '',
            'whatsapp'=>$s['profile_whatsapp'] ?? '',
            'photo'=>absoluteUrl($s['profile_photo'] ?? ''),
            'schoolLogo'=>absoluteUrl($s['profile_logo'] ?? '')
        ],
        'links'=>[
            'sibela'=>$s['link_sibela'] ?? '',
            'classroom'=>$s['link_classroom'] ?? '',
            'drive'=>$s['link_drive'] ?? '',
            'github'=>$s['link_github'] ?? '',
            'schoolWebsite'=>$s['link_school'] ?? ''
        ],
        'materials'=>$materials,
        'modules'=>$modules,
        'portfolio'=>$portfolio
    ]], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error'=>'Gagal mengambil data portal.']);
}
