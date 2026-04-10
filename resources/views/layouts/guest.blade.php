<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sign In' }} — HALI Access Partner Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300..700;1,9..40,300..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        .bg-gradient-animate {
            background: linear-gradient(-45deg, #663232, #8a5733, #4d2020, #794633);
            background-size: 400% 400%;
            animation: gradient-shift 15s ease infinite;
        }
        @keyframes gradient-shift {
            0%, 100% { background-position: 0% 50%; }
            50%       { background-position: 100% 50%; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-18px); }
        }
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-float   { animation: float 6s ease-in-out infinite; }
        .animate-fade-up { animation: fade-up 0.7s ease-out forwards; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
        .delay-400 { animation-delay: 400ms; }
        .particle {
            position: absolute;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-surface font-body text-on-surface">
    {{ $slot }}
    @livewireScripts
</body>
</html>
