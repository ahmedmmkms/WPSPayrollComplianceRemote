<?php
$locale = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'en';
$messages = [
    'en' => [
        'title' => 'WPS Payroll Compliance – Service Provisioning Stub',
        'body'  => 'The application backend is being assembled. This placeholder confirms the Render environment is healthy while Sprint 0 scaffolding completes.'
    ],
    'ar' => [
        'title' => 'منصة الامتثال لنظام حماية الأجور – صفحة مؤقتة',
        'body'  => 'يجري تجهيز التطبيق الرئيسي. تظهر هذه الصفحة للتأكد من جاهزية بيئة Render أثناء إنهاء أعمال الانطلاق.'
    ],
];
$key = str_starts_with(strtolower($locale), 'ar') ? 'ar' : 'en';
?>
<!DOCTYPE html>
<html lang="<?= $key ?>" dir="<?= $key === 'ar' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($messages[$key]['title'], ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        html, body { height: 100%; margin: 0; font-family: system-ui, sans-serif; background: #0f172a; color: #e2e8f0; }
        .container { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; text-align: center; padding: 2rem; }
        h1 { margin-bottom: 1rem; font-size: clamp(2rem, 5vw, 3rem); }
        p { max-width: 40rem; line-height: 1.6; font-size: clamp(1rem, 2.5vw, 1.25rem); }
        .badge { margin-top: 1.5rem; padding: 0.5rem 1rem; border-radius: 999px; background: rgba(14,165,233,0.15); color: #38bdf8; font-weight: 600; letter-spacing: 0.05em; }
    </style>
</head>
<body>
<div class="container">
    <h1><?= htmlspecialchars($messages[$key]['title'], ENT_QUOTES, 'UTF-8') ?></h1>
    <p><?= htmlspecialchars($messages[$key]['body'], ENT_QUOTES, 'UTF-8') ?></p>
    <div class="badge">Environment online · <?= date('Y-m-d H:i') ?> UTC</div>
</div>
</body>
</html>
