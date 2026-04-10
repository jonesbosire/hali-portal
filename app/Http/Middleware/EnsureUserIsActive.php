<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->status === 'suspended') {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been suspended. Please contact the HALI Secretariat.']);
        }

        if ($user && $user->status === 'pending') {
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account is pending approval. You will receive an email once approved.']);
        }

        return $next($request);
    }
}
