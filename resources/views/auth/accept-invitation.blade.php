<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join HALI Access Network</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style>
        .bg-brand { background: linear-gradient(135deg, #7c3d1f 0%, #5c2d10 60%, #0d6b62 100%); }
    </style>
</head>
<body class="h-full bg-surface font-body text-on-surface antialiased">

<div class="min-h-screen flex">

    {{-- Left brand panel --}}
    <div class="hidden lg:flex lg:w-5/12 bg-brand flex-col justify-between p-12 relative overflow-hidden">
        {{-- Decorative circles --}}
        <div class="absolute top-0 left-0 w-96 h-96 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-white/5 rounded-full translate-x-1/3 translate-y-1/3"></div>

        {{-- Logo --}}
        <div class="relative">
            <img src="{{ asset('images/hali-logo.png') }}" alt="HALI Access Network"
                 class="h-10 w-auto brightness-0 invert opacity-90">
        </div>

        {{-- Hero copy --}}
        <div class="relative">
            <h2 class="text-white text-3xl font-bold leading-snug mb-4">
                You've been invited to join the HALI Partner Portal
            </h2>
            <p class="text-white/70 text-sm leading-relaxed">
                Complete your account to access the private portal for HALI member organizations —
                connecting 40+ organizations championing African student access to global higher education.
            </p>
        </div>

        {{-- Stats --}}
        <div class="relative flex gap-8">
            <div>
                <p class="text-2xl font-bold text-white">40+</p>
                <p class="text-white/60 text-xs mt-0.5">Organizations</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">20+</p>
                <p class="text-white/60 text-xs mt-0.5">Countries</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-white">10K+</p>
                <p class="text-white/60 text-xs mt-0.5">Students Supported</p>
            </div>
        </div>
    </div>

    {{-- Right form panel --}}
    <div class="flex-1 flex flex-col justify-center px-6 sm:px-12 lg:px-16 py-12 overflow-y-auto">
        <div class="max-w-md w-full mx-auto">

            {{-- Mobile logo --}}
            <div class="lg:hidden mb-8">
                <img src="{{ asset('images/hali-logo.png') }}" alt="HALI Access Network" class="h-8 w-auto">
            </div>

            <div class="mb-6">
                <h1 class="text-2xl font-bold text-on-surface">Create your account</h1>
                <p class="text-sm text-on-surface-variant mt-1.5">
                    Joining as <strong class="text-on-surface">{{ $invitation->email }}</strong>
                    @if($invitation->organization)
                        · <span class="text-primary font-medium">{{ $invitation->organization->name }}</span>
                    @endif
                </p>
            </div>

            @if($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 rounded-xl p-4">
                    <p class="text-xs font-semibold text-red-700 mb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-exclamation"></i> Please fix the following:
                    </p>
                    <ul class="text-xs text-red-600 space-y-0.5 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('invitation.accept', $invitation->token) }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">First Name *</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" required autofocus
                               class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white @error('first_name') border-red-400 @enderror">
                        @error('first_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Last Name *</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" required
                               class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white @error('last_name') border-red-400 @enderror">
                        @error('last_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Job Title <span class="normal-case text-on-surface-variant/60">(optional)</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Program Director"
                           class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Email Address</label>
                    <input type="email" value="{{ $invitation->email }}" disabled
                           class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 bg-surface-container text-on-surface-variant cursor-not-allowed">
                    <p class="text-[10px] text-on-surface-variant mt-1">This is the address the invitation was sent to.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Password *</label>
                    <input type="password" name="password" required
                           class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white @error('password') border-red-400 @enderror">
                    <p class="mt-1 text-xs text-on-surface-variant">Minimum 8 characters</p>
                    @error('password') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                </div>

                <button type="submit"
                        class="w-full bg-[#7c3d1f] hover:bg-[#6a3319] text-white font-bold py-3 rounded-xl transition-colors text-sm mt-2 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    Create Account & Join Portal
                </button>
            </form>

            <p class="mt-6 text-center text-xs text-on-surface-variant">
                Already have an account?
                <a href="{{ route('login') }}" class="text-primary hover:underline font-medium">Sign in</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
