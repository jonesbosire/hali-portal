<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Account status update — HALI Access</title>
    <!--[if mso]>
    <noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
    <![endif]-->
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f1ee; color: #2d2d2d; -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; }
        .wrapper { width: 100%; padding: 40px 16px; background-color: #f4f1ee; }
        .email-card {
            max-width: 580px; margin: 0 auto; background: #ffffff;
            border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }
        .header {
            padding: 40px 40px 36px;
        }
        .header-active    { background: linear-gradient(135deg, #0d6b62 0%, #0a5550 100%); }
        .header-suspended { background: linear-gradient(135deg, #b45309 0%, #92400e 100%); }
        .header-pending   { background: linear-gradient(135deg, #1d4ed8 0%, #1e3a8a 100%); }
        .header-default   { background: linear-gradient(135deg, #7c3d1f 0%, #5c2d10 55%, #0d6b62 100%); }
        .header-logo {
            display: inline-block; background: rgba(255,255,255,0.15);
            border-radius: 10px; padding: 8px 14px;
            font-size: 18px; font-weight: 800; color: #ffffff;
            letter-spacing: 1px; text-decoration: none; margin-bottom: 20px;
        }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: 700; line-height: 1.3; margin: 0; }
        .header p  { color: rgba(255,255,255,0.75); font-size: 14px; margin-top: 6px; }
        .body { padding: 36px 40px; }
        .greeting { font-size: 16px; color: #2d2d2d; line-height: 1.6; margin-bottom: 24px; }
        .status-badge {
            display: flex; align-items: center; gap: 12px;
            border-radius: 12px; padding: 16px 20px; margin-bottom: 28px;
        }
        .status-active    { background: #f0fdf4; border: 1px solid #bbf7d0; }
        .status-suspended { background: #fffbeb; border: 1px solid #fde68a; }
        .status-pending   { background: #eff6ff; border: 1px solid #bfdbfe; }
        .status-badge .icon {
            width: 40px; height: 40px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: 20px;
        }
        .icon-active    { background: #16a34a; }
        .icon-suspended { background: #d97706; }
        .icon-pending   { background: #2563eb; }
        .status-badge h3 { font-size: 15px; font-weight: 700; margin: 0 0 2px; }
        .status-badge p  { font-size: 13px; margin: 0; }
        .color-active    { color: #166534; }
        .color-suspended { color: #92400e; }
        .color-pending   { color: #1e40af; }
        .cta-section {
            text-align: center; margin: 28px 0; padding: 24px;
            background: #fdf6ef; border-radius: 12px; border: 1px solid #e8d5c0;
        }
        .cta-section p { font-size: 13px; color: #7c3d1f; margin-bottom: 16px; font-weight: 600; }
        .btn-primary {
            display: inline-block; background: #7c3d1f; color: #ffffff !important;
            text-decoration: none; padding: 14px 36px;
            border-radius: 10px; font-size: 15px; font-weight: 700;
        }
        .note {
            font-size: 12px; color: #9a9a9a; line-height: 1.6;
            padding: 14px 16px; background: #fafafa;
            border-radius: 8px; border-left: 3px solid #e5e7eb; margin-top: 20px;
        }
        .footer { background: #2d2d2d; padding: 24px 40px; text-align: center; }
        .footer p { font-size: 12px; color: #9a9a9a; line-height: 1.8; }
        .footer a { color: #d4a574; text-decoration: none; }
        .footer .footer-links { margin-bottom: 8px; }
        @media (max-width: 480px) {
            .body { padding: 24px 20px; } .header { padding: 28px 20px 24px; } .footer { padding: 20px; }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="email-card">

        @php
            $headerClass = match($newStatus) {
                'active'    => 'header-active',
                'suspended' => 'header-suspended',
                'pending'   => 'header-pending',
                default     => 'header-default',
            };
            $headline = match($newStatus) {
                'active'    => 'Your account is active',
                'suspended' => 'Your account has been suspended',
                'pending'   => 'Your account is under review',
                default     => 'Your account status has changed',
            };
            $subline = match($newStatus) {
                'active'    => 'You have full access to the HALI Access Partner Portal.',
                'suspended' => 'Your portal access has been restricted.',
                'pending'   => 'The Secretariat will review your account shortly.',
                default     => "Your new status is: {$newStatus}.",
            };
        @endphp

        <div class="header {{ $headerClass }}">
            <div class="header-logo">HALI ACCESS</div>
            <h1>{{ $headline }}</h1>
            <p>{{ $subline }}</p>
        </div>

        <div class="body">

            <p class="greeting">Hi {{ $user->name }},</p>

            @if($newStatus === 'active')
                <div class="status-badge status-active">
                    <div class="icon icon-active" style="color:#fff;">✓</div>
                    <div>
                        <h3 class="color-active">Account Activated</h3>
                        <p class="color-active">You can now log in and access all portal features.</p>
                    </div>
                </div>
                <div class="cta-section">
                    <p>Head to the portal to get started</p>
                    <a href="{{ $dashboardUrl }}" class="btn-primary">Go to Dashboard</a>
                </div>

            @elseif($newStatus === 'suspended')
                <div class="status-badge status-suspended">
                    <div class="icon icon-suspended" style="color:#fff;">!</div>
                    <div>
                        <h3 class="color-suspended">Account Suspended</h3>
                        <p class="color-suspended">Your access to the portal has been restricted by the Secretariat.</p>
                    </div>
                </div>
                <div class="note">
                    If you believe this is an error or would like to discuss your account, please contact the HALI Secretariat directly at <a href="mailto:portal@haliaccess.org" style="color:#7c3d1f;">portal@haliaccess.org</a>
                </div>

            @elseif($newStatus === 'pending')
                <div class="status-badge status-pending">
                    <div class="icon icon-pending" style="color:#fff;">⏳</div>
                    <div>
                        <h3 class="color-pending">Under Review</h3>
                        <p class="color-pending">The Secretariat will contact you once the review is complete.</p>
                    </div>
                </div>
                <div class="note">
                    If you have questions about the review process, contact <a href="mailto:portal@haliaccess.org" style="color:#7c3d1f;">portal@haliaccess.org</a>
                </div>

            @else
                <p style="font-size:14px; color:#4b5563; line-height:1.7;">
                    Your account status has been updated to <strong>{{ $newStatus }}</strong>. If you have any questions, contact the Secretariat.
                </p>
            @endif

        </div>

        <div class="footer">
            <p class="footer-links">
                <a href="{{ $dashboardUrl }}">Portal Login</a>
                &nbsp;&nbsp;·&nbsp;&nbsp;
                <a href="mailto:portal@haliaccess.org">Contact Support</a>
                &nbsp;&nbsp;·&nbsp;&nbsp;
                <a href="https://haliaccess.org">haliaccess.org</a>
            </p>
            <p>
                &copy; {{ date('Y') }} HALI Access Network. All rights reserved.<br>
                This email was sent to {{ $user->email }} regarding your HALI Access Portal account.
            </p>
        </div>

    </div>
</div>
</body>
</html>
