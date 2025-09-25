<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $locale === 'ar' ? 'منصة الامتثال لنظام حماية الأجور' : 'WPS Payroll Compliance' }}</title>
    <style>
        :root {
            color-scheme: light dark;
        }
        html, body {
            height: 100%;
            margin: 0;
            font-family: "Inter", "Cairo", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: radial-gradient(circle at top, #0f172a, #020617 60%);
            color: #e2e8f0;
        }
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            text-align: center;
            padding: 2.5rem 1.5rem;
            gap: 1.5rem;
        }
        h1 {
            margin: 0;
            font-size: clamp(2.25rem, 6vw, 3.25rem);
            letter-spacing: 0.04em;
        }
        p {
            margin: 0;
            max-width: 42rem;
            line-height: 1.7;
            font-size: clamp(1.05rem, 2.8vw, 1.35rem);
            color: rgba(248, 250, 252, 0.85);
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.25rem;
            border-radius: 999px;
            background: rgba(56, 189, 248, 0.16);
            color: #38bdf8;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .badge span {
            font-family: "JetBrains Mono", monospace;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>
        {{ $locale === 'ar' ? 'منصة الامتثال لنظام حماية الأجور' : 'WPS Payroll Compliance' }}
    </h1>
    <p>
        {{ $locale === 'ar'
            ? 'التطبيق قيد التطوير. تم تفعيل البيئة وتعمل على منصة Render بينما يُستكمل العمل على وظائف الدفعات، التعددية، واللغات.'
            : 'The application backend is under active development. The Render environment is online while we finish tenant, import, and localization features.' }}
    </p>
    <div class="badge">
        {{ $locale === 'ar' ? 'البيئة متصلة' : 'Environment Online' }}
        <span>{{ now('UTC')->format('Y-m-d H:i') }} UTC</span>
    </div>
</div>
</body>
</html>
