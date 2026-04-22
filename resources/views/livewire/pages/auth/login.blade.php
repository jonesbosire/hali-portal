<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        // Rate limit: 5 attempts per minute per IP+email (Hydra brute force prevention)
        $key = 'login|' . strtolower($this->form->email) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, maxAttempts: 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'form.email' => "Too many login attempts. Please wait {$seconds} seconds.",
            ]);
        }

        $this->validate();

        try {
            $this->form->authenticate();
        } catch (ValidationException $e) {
            RateLimiter::hit($key, decay: 60);
            throw $e;
        }

        RateLimiter::clear($key);
        Session::regenerate();

        auth()->user()->update(['last_login_at' => now()]);

        $this->redirectIntended(default: route('dashboard'));
    }
}; ?>

<div class="min-h-screen flex flex-col md:flex-row overflow-hidden">

    {{-- ── Left panel ── --}}
    <section class="hidden md:flex login-panel-bg w-full md:w-[42%] flex-col justify-between p-10 lg:p-16">

        {{-- Logo --}}
        <div>
            <img src="{{ asset('images/hali-logo.png') }}" alt="HALI Access Network"
                 class="h-10 w-auto brightness-0 invert opacity-90">
        </div>

        {{-- Main content --}}
        <div class="max-w-md">
            <h2 class="font-headline text-4xl lg:text-5xl font-bold text-white leading-tight mb-6">
                Supporting HALI students from school to university and beyond
            </h2>
            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="bg-white/10 rounded-xl p-3 text-center">
                    <p class="font-headline text-2xl font-bold text-white">16+</p>
                    <p class="text-white/60 text-[10px] uppercase tracking-wider mt-0.5">Member Orgs</p>
                </div>
                <div class="bg-white/10 rounded-xl p-3 text-center">
                    <p class="font-headline text-2xl font-bold text-white">2,000+</p>
                    <p class="text-white/60 text-[10px] uppercase tracking-wider mt-0.5">Students Yearly</p>
                </div>
                <div class="bg-white/10 rounded-xl p-3 text-center">
                    <p class="font-headline text-2xl font-bold text-white">17</p>
                    <p class="text-white/60 text-[10px] uppercase tracking-wider mt-0.5">Countries</p>
                </div>
            </div>
        </div>

        <p class="text-white/40 text-xs tracking-wider uppercase">
            © {{ date('Y') }} HALI Access Partner Network
        </p>
    </section>

    {{-- ── Right panel: Login form ── --}}
    <section class="w-full md:w-[58%] bg-surface-container-low md:bg-surface-container-lowest flex items-center justify-center px-6 pt-10 pb-10 md:p-16 lg:p-24 min-h-screen">
        <div class="w-full max-w-[420px]">

            {{-- Mobile logo --}}
            <div class="md:hidden flex justify-center mb-8">
                <img src="{{ asset('images/hali-logo.png') }}" alt="HALI Access Network" style="height:130px;" class="w-auto max-w-[300px]">
            </div>

            <div class="mb-10">
                <h3 class="font-headline text-3xl font-bold text-on-surface mb-2">Sign in to your account</h3>
                <p class="text-on-surface-variant text-sm">Use the email address and password you set when you joined.</p>
            </div>

            {{-- Session status --}}
            @if (session('status'))
                <div class="mb-5 p-3 bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl">
                    {{ session('status') }}
                </div>
            @endif

            <form wire:submit="login" class="space-y-5">

                {{-- Email --}}
                <div class="space-y-1.5">
                    <label for="email" class="block text-sm font-semibold text-on-surface-variant ml-1">Email address</label>
                    <div class="relative group">
                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors"></i>
                        <input wire:model="form.email"
                               id="email" type="email" name="email"
                               required autofocus autocomplete="username"
                               placeholder="name@organization.org"
                               class="block w-full pl-12 pr-4 py-4 bg-surface-container-high border-2 border-transparent focus:border-primary/20 rounded-xl text-on-surface placeholder:text-outline focus:ring-4 focus:ring-primary/10 transition-colors text-sm @error('form.email') border-error/30 @enderror">
                    </div>
                    @error('form.email')
                        <p class="mt-1 text-xs text-error ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="space-y-1.5">
                    <div class="flex justify-between items-center px-1">
                        <label for="password" class="block text-sm font-semibold text-on-surface-variant">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" wire:navigate
                               class="text-xs text-primary hover:underline transition-colors">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <div class="relative group">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors"></i>
                        <input wire:model="form.password"
                               id="password" type="password" name="password"
                               required autocomplete="current-password"
                               placeholder="••••••••••••"
                               class="block w-full pl-12 pr-4 py-4 bg-surface-container-high border-2 border-transparent focus:border-primary/20 rounded-xl text-on-surface placeholder:text-outline focus:ring-4 focus:ring-primary/10 transition-colors text-sm @error('form.password') border-error/30 @enderror">
                    </div>
                    @error('form.password')
                        <p class="mt-1 text-xs text-error ml-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center px-1">
                    <input wire:model="form.remember"
                           id="remember" type="checkbox"
                           class="h-4 w-4 rounded border-outline-variant text-primary focus:ring-primary bg-surface-container-lowest">
                    <label for="remember" class="ml-3 text-sm text-on-surface-variant">
                        Keep me signed in for 30 days
                    </label>
                </div>

                {{-- Submit --}}
                <div>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            class="w-full flex justify-center items-center gap-2 py-4 px-6 rounded-xl bg-secondary-container hover:bg-[#a26f33] text-on-secondary-container font-headline font-bold text-base transition-colors disabled:opacity-70">
                        <span wire:loading.remove>Sign in</span>
                        <span wire:loading class="inline-flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Signing in...
                        </span>
                    </button>
                </div>
            </form>

            {{-- Footer --}}
            <div class="mt-10 pt-8 border-t border-outline-variant/20 text-center">
                <p class="text-on-surface-variant text-sm">
                    Not in the system yet?
                    <a href="mailto:portal@haliaccess.org" class="text-primary hover:underline ml-1">Contact the Secretariat</a>
                </p>
            </div>
        </div>
    </section>

</div>
