<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $appUrl = rtrim(config('app.url', 'http://localhost'), '/');

        // Clickjacking — only allow framing from same origin
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Content Security Policy — prevent clickjacking and XSS
        // frame-ancestors: blocks embedding in foreign iframes (double-click jacking)
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            // 'unsafe-inline' required by Livewire inline event handlers and Alpine.js x- directives.
            // 'unsafe-eval' required by Alpine.js (bundled in Livewire) — it uses new AsyncFunction()
            // to evaluate wire: and x- expressions. Removing it silently breaks all Livewire forms.
            "script-src 'self' 'unsafe-inline' 'unsafe-eval'; " .
            "style-src 'self' 'unsafe-inline' fonts.googleapis.com; " .
            "font-src 'self' fonts.gstatic.com data:; " .
            "img-src 'self' data: blob: ui-avatars.com; " .
            "connect-src 'self'; " .
            "frame-ancestors 'self';"
        );

        // Prevent MIME type sniffing — e.g. a .jpg being executed as JS
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Stop sending Referer header to external sites
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Disable browser features not needed by the app
        $response->headers->set('Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=(), usb=()'
        );

        // Prevent XSS in older browsers
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Force HTTPS (only set in production to avoid breaking local dev)
        if (config('app.env') === 'production') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Remove fingerprinting headers
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }
}
