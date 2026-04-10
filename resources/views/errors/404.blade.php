<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Page Not Found — HALI Access</title>
    @vite(['resources/css/app.css'])
</head>
<body class="h-full bg-surface font-body antialiased flex items-center justify-center">
    <div class="text-center px-6">
        <img src="{{ asset('images/hali-logo.png') }}" alt="HALI Access" class="h-10 w-auto mx-auto mb-8 opacity-70">
        <p class="text-8xl font-black text-[#7c3d1f] opacity-20 leading-none mb-4">404</p>
        <h1 class="text-2xl font-bold text-on-surface mb-2">Page not found</h1>
        <p class="text-on-surface-variant text-sm mb-8 max-w-sm mx-auto">
            The page you're looking for doesn't exist, or your invitation link may have expired.
        </p>
        <div class="flex items-center justify-center gap-3">
            @auth
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-2 bg-[#7c3d1f] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#6a3319] transition-colors">
                    <i class="fa-solid fa-gauge-high text-xs"></i> Go to Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-2 bg-[#7c3d1f] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#6a3319] transition-colors">
                    <i class="fa-solid fa-right-to-bracket text-xs"></i> Sign In
                </a>
            @endauth
        </div>
    </div>
</body>
</html>
