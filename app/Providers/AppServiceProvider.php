<?php

namespace App\Providers;

use App\Services\AfricasTalkingSmsChannel;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(Request $request): void
    {
        // ── SMS notification channel (Africa's Talking) ───────────────────
        // Registers the 'sms' channel alias so notifications can use via: ['sms']
        Notification::extend('sms', fn () => new AfricasTalkingSmsChannel());

        // ── HTTPS scheme enforcement ──────────────────────────────────────
        // In production, always generate https:// URLs regardless of what
        // PHP sees from the server (handles Nginx → FPM and ngrok tunnels).
        // In local dev this is skipped so http://localhost still works.
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // ── Ngrok / reverse proxy host fix (local dev only) ───────────────
        // When tunnelling through ngrok, X-Forwarded-Host contains the public
        // tunnel domain. Force route() / url() to use it so redirects work.
        if (config('app.env') !== 'production' &&
            ($request->server->has('HTTP_X_FORWARDED_HOST') ||
             $request->server->has('HTTP_X_ORIGINAL_HOST'))) {

            $host = $request->server->get('HTTP_X_FORWARDED_HOST')
                 ?? $request->server->get('HTTP_X_ORIGINAL_HOST');

            URL::forceRootUrl('https://' . $host);
        }

        // ── Rate limiters (brute-force & DDoS protection) ────────────────

        // Login: 5 attempts/min per IP+email — prevents Hydra brute force
        RateLimiter::for('login', function (Request $req) {
            return Limit::perMinute(5)
                ->by(strtolower((string) $req->input('email')) . '|' . $req->ip());
        });

        // Invitation acceptance: 10/min per IP
        RateLimiter::for('invitation', function (Request $req) {
            return Limit::perMinute(10)->by($req->ip());
        });

        // Event registration: 10 registrations/min per user — prevents spam sign-ups
        RateLimiter::for('event-register', function (Request $req) {
            return Limit::perMinute(10)->by($req->user()?->id ?? $req->ip());
        });

        // Opportunity posting: 5 posts/hour per user — prevents spam listings
        RateLimiter::for('opportunities', function (Request $req) {
            return Limit::perHour(5)->by($req->user()?->id ?? $req->ip());
        });

        // General throttle: 60/min authenticated, 30/min guest
        RateLimiter::for('api', function (Request $req) {
            return $req->user()
                ? Limit::perMinute(60)->by($req->user()->id)
                : Limit::perMinute(30)->by($req->ip());
        });
    }
}
