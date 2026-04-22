<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In — HALI Access Partner Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300..700;1,9..40,300..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>.login-panel-bg { background-color: #5c2b1a; }</style>
</head>
<body class="bg-surface font-body text-on-surface">

<div class="min-h-screen flex flex-col md:flex-row overflow-hidden">

    {{-- ── Left panel ── --}}
    <section class="hidden md:flex login-panel-bg w-full md:w-[42%] flex-col justify-between p-10 lg:p-16">
        <div>
            <img src="{{ asset('images/hali-logo.png') }}" alt="HALI Access Network"
                 class="h-10 w-auto brightness-0 invert opacity-90">
        </div>
        <div class="max-w-md">
            <h2 class="font-headline text-4xl lg:text-5xl font-bold text-white leading-tight mb-6">
                Supporting HALI students from school to university and beyond
            </h2>
            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="bg-white/10 rounded-xl p-3 text-center">
                    <p class="font-headline text-2xl font-bold text-white">40+</p>
                    <p class="text-white/60 text-[10px] uppercase tracking-wider mt-0.5">Member Orgs</p>
                </div>
                <div class="bg-white/10 rounded-xl p-3 text-center">
                    <p class="font-headline text-2xl font-bold text-white">10K+</p>
                    <p class="text-white/60 text-[10px] uppercase tracking-wider mt-0.5">Students</p>
                </div>
                <div class="bg-white/10 rounded-xl p-3 text-center">
                    <p class="font-headline text-2xl font-bold text-white">20+</p>
                    <p class="text-white/60 text-[10px] uppercase tracking-wider mt-0.5">Countries</p>
                </div>
            </div>
        </div>
        <p class="text-white/40 text-xs tracking-wider uppercase">
            © {{ date('Y') }} HALI Access Partner Network
        </p>
    </section>

    {{-- ── Right panel ── --}}
    <section class="w-full md:w-[58%] bg-surface-container-low md:bg-surface-container-lowest flex items-center justify-center px-6 pt-10 pb-10 md:p-16 lg:p-24 min-h-screen">
        <div class="w-full max-w-[420px]">

            <div class="md:hidden flex justify-center mb-8">
                <img src="{{ asset('images/hali-logo.png') }}" alt="HALI Access Network"
                     style="height:130px;" class="w-auto max-w-[300px]">
            </div>

            <div class="mb-10">
                <h3 class="font-headline text-3xl font-bold text-on-surface mb-2">Sign in to your account</h3>
                <p class="text-on-surface-variant text-sm">Use the email address and password you set when you joined.</p>
            </div>

            @if (session('status'))
                <div class="mb-5 p-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div class="space-y-1.5">
                    <label for="email" class="block text-sm font-semibold text-on-surface-variant ml-1">Email address</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-outline"></i>
                        <input id="email" name="email" type="email"
                               value="{{ old('email') }}"
                               required autofocus autocomplete="username"
                               placeholder="name@organization.org"
                               class="block w-full pl-12 pr-4 py-4 bg-surface-container-high border-2 {{ $errors->has('email') ? 'border-error/30' : 'border-transparent' }} focus:border-primary/20 rounded-xl text-on-surface placeholder:text-outline focus:ring-4 focus:ring-primary/10 transition-colors text-sm">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-error ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center px-1">
                        <label for="password" class="block text-sm font-semibold text-on-surface-variant">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               class="text-xs text-primary hover:underline transition-colors">Forgot password?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-outline"></i>
                        <input id="password" name="password" type="password"
                               required autocomplete="current-password"
                               placeholder="••••••••••••"
                               class="block w-full pl-12 pr-4 py-4 bg-surface-container-high border-2 {{ $errors->has('password') ? 'border-error/30' : 'border-transparent' }} focus:border-primary/20 rounded-xl text-on-surface placeholder:text-outline focus:ring-4 focus:ring-primary/10 transition-colors text-sm">
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-error ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center px-1">
                    <input id="remember" name="remember" type="checkbox"
                           class="h-4 w-4 rounded border-outline-variant text-primary focus:ring-primary bg-surface-container-lowest">
                    <label for="remember" class="ml-3 text-sm text-on-surface-variant">
                        Keep me signed in for 30 days
                    </label>
                </div>

                {{-- Submit --}}
                <div>
                    <button type="submit"
                            class="w-full flex justify-center items-center gap-2 py-4 px-6 rounded-xl bg-secondary-container hover:bg-[#a26f33] text-on-secondary-container font-headline font-bold text-base transition-colors">
                        Sign in
                    </button>
                </div>
            </form>

            <div class="mt-10 pt-8 border-t border-outline-variant/20 text-center">
                <p class="text-on-surface-variant text-sm">
                    Not in the system yet?
                    <a href="mailto:portal@haliaccess.org" class="text-primary hover:underline ml-1">Contact the Secretariat</a>
                </p>
            </div>
        </div>
    </section>
</div>

</body>
</html>
