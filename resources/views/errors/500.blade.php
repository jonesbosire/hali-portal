<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Server Error — HALI Access</title>
    @vite(['resources/css/app.css'])
</head>
<body class="h-full bg-surface font-body antialiased flex items-center justify-center">
    <div class="text-center px-6">
        <img src="{{ asset('images/hali-logo.png') }}" alt="HALI Access" class="h-10 w-auto mx-auto mb-8 opacity-70">
        <p class="text-8xl font-black text-red-500 opacity-20 leading-none mb-4">500</p>
        <h1 class="text-2xl font-bold text-on-surface mb-2">Something went wrong</h1>
        <p class="text-on-surface-variant text-sm mb-8 max-w-sm mx-auto">
            We encountered an unexpected error. Our team has been notified. Please try again in a moment.
        </p>
        <a href="{{ url()->previous() !== url()->current() ? url()->previous() : '/' }}"
           class="inline-flex items-center gap-2 bg-[#7c3d1f] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#6a3319] transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> Go Back
        </a>
    </div>
</body>
</html>
