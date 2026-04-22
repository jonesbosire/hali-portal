<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Registration confirmed — {{ $event->title }}</title>
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
        .header h1 { color: #ffffff; font-size: 22px; font-weight: 700; line-height: 1.3; margin: 0; }
        .header p  { color: rgba(255,255,255,0.75); font-size: 14px; margin-top: 6px; }
        .body { padding: 36px 40px; }
        .greeting { font-size: 16px; color: #2d2d2d; line-height: 1.6; margin-bottom: 24px; }
        .confirm-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 28px;
        }
        .confirm-badge .check {
            width: 36px; height: 36px;
            background: #16a34a;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 18px; color: #fff;
        }
        .confirm-badge p { font-size: 15px; font-weight: 700; color: #166534; margin: 0; }
        .confirm-badge span { font-size: 13px; color: #15803d; }
        .event-card {
            background: #fafafa;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 28px;
        }
        .event-card-header {
            background: #7c3d1f;
            padding: 14px 20px;
        }
        .event-card-header h2 { color: #fff; font-size: 16px; font-weight: 700; margin: 0; }
        .event-card-header span { color: rgba(255,255,255,0.75); font-size: 12px; }
        .event-details { padding: 20px; }
        .detail-row {
            display: flex;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
            font-size: 13px;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { width: 80px; flex-shrink: 0; color: #6b7280; font-weight: 600; text-transform: uppercase; font-size: 11px; padding-top: 1px; }
        .detail-value { color: #2d2d2d; line-height: 1.5; }
        .cta-section {
            text-align: center;
            margin: 28px 0;
            padding: 24px;
            background: #fdf6ef;
            border-radius: 12px;
            border: 1px solid #e8d5c0;
        }
        .cta-section p { font-size: 13px; color: #7c3d1f; margin-bottom: 16px; font-weight: 600; }
        .btn-primary {
            display: inline-block;
            background: #7c3d1f;
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 36px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
        }
        .note {
            font-size: 12px;
            color: #9a9a9a;
            line-height: 1.6;
            padding: 14px 16px;
            background: #fafafa;
            border-radius: 8px;
            border-left: 3px solid #e5e7eb;
            margin-top: 20px;
        }
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
            .footer { padding: 20px; }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="email-card">

        <div class="header">
            <div class="header-logo">HALI ACCESS</div>
            <h1>You're registered for this event!</h1>
            <p>Your spot has been confirmed. See the details below.</p>
        </div>

        <div class="body">

            <p class="greeting">Hi {{ $user->name }},<br><br>
                Great news — your registration for the upcoming HALI Access event has been confirmed. We look forward to seeing you there.
            </p>

            <div class="confirm-badge">
                <div class="check">✓</div>
                <div>
                    <p>Registration Confirmed</p>
                    <span>Registered on {{ $registration->registered_at->format('F j, Y \a\t g:i A') }}</span>
                </div>
            </div>

            <div class="event-card">
                <div class="event-card-header">
                    <h2>{{ $event->title }}</h2>
                    <span>{{ ucfirst($event->type) }}</span>
                </div>
                <div class="event-details">
                    <div class="detail-row">
                        <div class="detail-label">Date</div>
                        <div class="detail-value">{{ $event->start_datetime->format('l, F j, Y') }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Time</div>
                        <div class="detail-value">
                            {{ $event->start_datetime->format('g:i A') }}
                            @if($event->end_datetime) – {{ $event->end_datetime->format('g:i A') }} @endif
                            {{ $event->timezone }}
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Format</div>
                        <div class="detail-value" style="text-transform: capitalize;">{{ str_replace('_', ' ', $event->location_type) }}</div>
                    </div>
                    @if($event->venue_name)
                    <div class="detail-row">
                        <div class="detail-label">Venue</div>
                        <div class="detail-value">
                            {{ $event->venue_name }}
                            @if($event->venue_address)<br><span style="color:#6b7280; font-size:12px;">{{ $event->venue_address }}</span>@endif
                        </div>
                    </div>
                    @endif
                    @if($registration->dietary_requirements)
                    <div class="detail-row">
                        <div class="detail-label">Dietary</div>
                        <div class="detail-value">{{ $registration->dietary_requirements }}</div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="cta-section">
                <p>View full event details and join link on the portal</p>
                <a href="{{ $eventUrl }}" class="btn-primary">View Event on Portal</a>
            </div>

            @if(in_array($event->location_type, ['virtual', 'hybrid']))
            <div class="note">
                <strong>Virtual join link:</strong> The join link for this event will be available on the event page in the portal once you are logged in. It is only visible to registered attendees.
            </div>
            @endif

            <div class="note" style="margin-top: 12px;">
                To cancel your registration, visit the event page on the portal and click "Cancel registration". If you have any questions, contact the HALI Secretariat at <a href="mailto:portal@haliaccess.org" style="color:#7c3d1f;">portal@haliaccess.org</a>
            </div>

        </div>

        <div class="footer">
            <p class="footer-links">
                <a href="{{ $eventUrl }}">View Event</a>
                &nbsp;&nbsp;·&nbsp;&nbsp;
                <a href="{{ route('dashboard') }}">Go to Portal</a>
                &nbsp;&nbsp;·&nbsp;&nbsp;
                <a href="mailto:portal@haliaccess.org">Contact Support</a>
            </p>
            <p>
                &copy; {{ date('Y') }} HALI Access Network. All rights reserved.<br>
                This email was sent to {{ $user->email }} because you registered for a HALI Access event.
            </p>
        </div>

    </div>
</div>
</body>
</html>
