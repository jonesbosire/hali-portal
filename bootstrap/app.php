<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // ── Trusted proxies (ngrok / load balancer) ──────────────────────
        // Without this, asset() URLs use the server's local IP instead of
        // the ngrok public hostname, so CSS/JS won't load through the tunnel.
        $middleware->trustProxies(
            at: '*',
            headers:
                Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO
        );

        // ── Security headers on every web response ────────────────────────
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        // ── AuthenticateSession — required for Auth::logoutOtherDevices() ──
        // Stores a hashed password in the session; if the password changes on
        // another device, that session is invalidated on the next request.
        $middleware->web(append: [
            \Illuminate\Session\Middleware\AuthenticateSession::class,
        ]);

        // ── Middleware aliases ─────────────────────────────────────────────
        $middleware->alias([
            'active.user' => \App\Http\Middleware\EnsureUserIsActive::class,
            'admin'       => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'super_admin' => \App\Http\Middleware\EnsureSuperAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
