<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
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
        // ── Ngrok / reverse proxy URL fix ────────────────────────────────
        // When behind ngrok, X-Forwarded-Host contains the public tunnel domain.
        // Force Laravel to generate asset() and route() URLs using that domain.
        if ($request->server->has('HTTP_X_FORWARDED_HOST') ||
            $request->server->has('HTTP_X_ORIGINAL_HOST')) {

            URL::forceScheme('https');

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

        // General throttle: 60/min authenticated, 30/min guest
        RateLimiter::for('api', function (Request $req) {
            return $req->user()
                ? Limit::perMinute(60)->by($req->user()->id)
                : Limit::perMinute(30)->by($req->ip());
        });
    }
}
