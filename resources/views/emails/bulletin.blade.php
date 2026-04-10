<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $bulletin->title }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; margin: 0; padding: 0; background: #F5F7F9; color: #2D3748; }
        .container { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #0D5C6B, #1A7A8A); padding: 24px 32px; }
        .header .badge { font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #7DD3DE; font-weight: 600; }
        .header h1 { color: white; margin: 6px 0 0; font-size: 20px; }
        .body { padding: 32px; }
        .body p, .body li { color: #4A5568; line-height: 1.7; }
        .cta { text-align: center; margin: 24px 0; }
        .btn { display: inline-block; background: #F5A623; color: white; text-decoration: none; padding: 12px 28px; border-radius: 8px; font-weight: 600; }
        .footer { background: #F5F7F9; padding: 16px 32px; text-align: center; font-size: 12px; color: #718096; border-top: 1px solid #E2E8ED; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="badge">HALI Access Member Bulletin</div>
            <h1>{{ $bulletin->title }}</h1>
        </div>
        <div class="body">
            <p>Hi {{ $recipient->name }},</p>
            <div>{!! $bulletin->content !!}</div>

            <div class="cta">
                <a href="{{ $portalUrl }}" class="btn">Visit the Portal →</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} HALI Access Network · <a href="https://haliaccess.org" style="color:#1A7A8A;">haliaccess.org</a><br>
            You're receiving this as a member of HALI Access.
        </div>
    </div>
</body>
</html>
