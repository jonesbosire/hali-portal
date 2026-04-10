<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>You're invited to the HALI Access Partner Portal</title>
    <!--[if mso]>
    <noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
    <![endif]-->
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f1ee;
            color: #2d2d2d;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; }
        .wrapper { width: 100%; padding: 40px 16px; background-color: #f4f1ee; }
        .email-card {
            max-width: 580px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #7c3d1f 0%, #5c2d10 55%, #0d6b62 100%);
            padding: 40px 40px 36px;
        }
        .header-logo {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            border-radius: 10px;
            padding: 8px 14px;
            font-size: 18px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 1px;
            text-decoration: none;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #ffffff;
            font-size: 22px;
            font-weight: 700;
            line-height: 1.3;
            margin: 0;
        }
        .header p {
            color: rgba(255,255,255,0.75);
            font-size: 14px;
            margin-top: 6px;
        }

        /* Body */
        .body { padding: 36px 40px; }
        .greeting {
            font-size: 16px;
            color: #2d2d2d;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        /* Org badge */
        .org-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fdf6ef;
            border: 1px solid #e8d5c0;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #7c3d1f;
        }
        .org-badge strong { font-weight: 700; }

        /* Steps */
        .steps-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #9a9a9a;
            margin-bottom: 16px;
        }
        .steps { margin-bottom: 28px; }
        .step {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 16px;
        }
        .step-num {
            flex-shrink: 0;
            width: 28px;
            height: 28px;
            background: #7c3d1f;
            color: white;
            border-radius: 50%;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 1px;
        }
        .step-body { flex: 1; }
        .step-body strong {
            display: block;
            font-size: 14px;
            font-weight: 700;
            color: #2d2d2d;
            margin-bottom: 3px;
        }
        .step-body span {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.5;
        }
        .step-body .email-chip {
            display: inline-block;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 2px 8px;
            font-size: 12px;
            font-family: 'Courier New', monospace;
            color: #374151;
            margin-top: 4px;
        }

        /* CTA */
        .cta-section {
            text-align: center;
            margin: 32px 0;
            padding: 28px;
            background: #fdf6ef;
            border-radius: 12px;
            border: 1px solid #e8d5c0;
        }
        .cta-section p {
            font-size: 13px;
            color: #7c3d1f;
            margin-bottom: 16px;
            font-weight: 600;
        }
        .btn-primary {
            display: inline-block;
            background: #7c3d1f;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 36px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.01em;
        }
        .btn-primary:hover { background: #6a3319; }
        .cta-expires {
            font-size: 12px;
            color: #9a9a9a;
            margin-top: 12px;
        }

        /* What you'll access */
        .features {
            margin-bottom: 28px;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .feature-item {
            background: #f9fafb;
            border: 1px solid #f3f4f6;
            border-radius: 8px;
            padding: 12px;
        }
        .feature-item .icon {
            font-size: 18px;
            margin-bottom: 6px;
            display: block;
        }
        .feature-item strong {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #2d2d2d;
            margin-bottom: 2px;
        }
        .feature-item span {
            font-size: 11px;
            color: #9a9a9a;
        }

        /* Already have account */
        .already-box {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            padding: 16px 18px;
            margin-bottom: 24px;
            font-size: 13px;
            color: #166534;
        }
        .already-box a {
            color: #15803d;
            font-weight: 700;
            text-decoration: underline;
        }

        /* Note */
        .note {
            font-size: 12px;
            color: #9a9a9a;
            line-height: 1.6;
            padding: 16px;
            background: #fafafa;
            border-radius: 8px;
            border-left: 3px solid #e5e7eb;
        }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid #f3f4f6;
            margin: 24px 0;
        }

        /* Footer */
        .footer {
            background: #2d2d2d;
            padding: 24px 40px;
            text-align: center;
        }
        .footer p { font-size: 12px; color: #9a9a9a; line-height: 1.8; }
        .footer a { color: #d4a574; text-decoration: none; }
        .footer .footer-links { margin-bottom: 8px; }

        @media (max-width: 480px) {
            .body { padding: 24px 20px; }
            .header { padding: 28px 20px 24px; }
            .feature-grid { grid-template-columns: 1fr; }
            .footer { padding: 20px; }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="email-card">

        {{-- Header --}}
        <div class="header">
            <div class="header-logo">HALI ACCESS</div>
            <h1>You've been invited to join the Partner Portal</h1>
            <p>Private network for HALI member organizations</p>
        </div>

        {{-- Body --}}
        <div class="body">

            <p class="greeting">
                Hello,<br><br>
                You have been invited to join the <strong>HALI Access Partner Portal</strong> — the private digital hub connecting over 40 organizations across Africa and beyond that champion high-achieving, low-income students in accessing global higher education.
            </p>

            @if($organization)
            <div class="org-badge">
                <span style="font-size:16px;">🏢</span>
                <div>
                    Joining as a representative of <strong>{{ $organization->name }}</strong>
                    @if($organization->country)
                        &nbsp;·&nbsp; {{ $organization->country }}
                    @endif
                </div>
            </div>
            @endif

            {{-- Steps --}}
            <p class="steps-title">How to get started — 3 simple steps</p>
            <div class="steps">
                <div class="step">
                    <div class="step-num">1</div>
                    <div class="step-body">
                        <strong>Accept your invitation</strong>
                        <span>Click the button below. You'll be taken to a secure page to set up your account. Your invitation link is unique to you — do not share it.</span>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">2</div>
                    <div class="step-body">
                        <strong>Create your password</strong>
                        <span>Enter your name and choose a strong password (minimum 8 characters). Your email address is already set to:</span>
                        <div class="email-chip">{{ $email }}</div>
                    </div>
                </div>
                <div class="step">
                    <div class="step-num">3</div>
                    <div class="step-body">
                        <strong>Sign in anytime</strong>
                        <span>After setup, you can log in at any time using your email and password at the portal login page.</span>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="cta-section">
                <p>Your invitation is ready — click below to get started</p>
                <a href="{{ $acceptUrl }}" class="btn-primary">Accept Invitation &amp; Create Account</a>
                <p class="cta-expires">⏳ This invitation expires on <strong>{{ $expiresAt }}</strong></p>
            </div>

            {{-- What you'll access --}}
            <p class="steps-title">What you'll have access to</p>
            <div class="features">
                <div class="feature-grid">
                    <div class="feature-item">
                        <span class="icon">📋</span>
                        <strong>Member Directory</strong>
                        <span>Browse all HALI partner organizations and contacts</span>
                    </div>
                    <div class="feature-item">
                        <span class="icon">📅</span>
                        <strong>Events & Webinars</strong>
                        <span>Register for network events and track your attendance</span>
                    </div>
                    <div class="feature-item">
                        <span class="icon">💡</span>
                        <strong>Opportunities Board</strong>
                        <span>Scholarships, jobs, fellowships, and internships</span>
                    </div>
                    <div class="feature-item">
                        <span class="icon">📁</span>
                        <strong>Resource Library</strong>
                        <span>Exclusive reports, toolkits, and research papers</span>
                    </div>
                    <div class="feature-item">
                        <span class="icon">📰</span>
                        <strong>Network Stories</strong>
                        <span>Latest news and updates from member organizations</span>
                    </div>
                    <div class="feature-item">
                        <span class="icon">🔔</span>
                        <strong>Notifications</strong>
                        <span>Stay updated on announcements from the Secretariat</span>
                    </div>
                </div>
            </div>

            <hr class="divider">

            {{-- Already have account --}}
            <div class="already-box">
                Already have an account? Skip setup and <a href="{{ $loginUrl }}">sign in directly here</a>. Use the same email address this invitation was sent to.
            </div>

            {{-- Note --}}
            <div class="note">
                <strong>Important:</strong> If you did not expect this invitation or believe it was sent in error, please ignore this email — your email address will not be registered unless you click the button above. For any questions, contact the HALI Secretariat at <a href="mailto:portal@haliaccess.org" style="color:#7c3d1f;">portal@haliaccess.org</a>
            </div>

        </div>

        {{-- Footer --}}
        <div class="footer">
            <p class="footer-links">
                <a href="{{ $loginUrl }}">Sign In to Portal</a>
                &nbsp;&nbsp;·&nbsp;&nbsp;
                <a href="mailto:portal@haliaccess.org">Contact Support</a>
                &nbsp;&nbsp;·&nbsp;&nbsp;
                <a href="https://haliaccess.org">haliaccess.org</a>
            </p>
            <p>
                &copy; {{ date('Y') }} HALI Access Network. All rights reserved.<br>
                This email was sent to {{ $email }} because you were invited to the HALI Partner Portal.
            </p>
        </div>

    </div>
</div>
</body>
</html>
