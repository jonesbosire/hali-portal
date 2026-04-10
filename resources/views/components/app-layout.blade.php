@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Portal' }} — HALI Access Network</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300..700;1,9..40,300..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full font-body text-on-surface antialiased bg-background"
      x-data="{ sidebarOpen: false }"
      x-cloak>

    <div class="flex h-screen overflow-hidden">

        {{-- Desktop sidebar --}}
        <div class="hidden lg:flex lg:flex-shrink-0">
            @include('partials.sidebar')
        </div>

        {{-- Mobile sidebar overlay --}}
        <div x-show="sidebarOpen" class="fixed inset-0 z-40 flex lg:hidden" style="display:none">
            <div x-show="sidebarOpen"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false"
                 class="fixed inset-0 bg-on-surface/50 backdrop-blur-sm"></div>
            <div class="relative flex-1 flex flex-col max-w-[220px] w-full">
                @include('partials.sidebar')
            </div>
        </div>

        {{-- Main content area --}}
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

            @include('partials.topbar')

            <main class="flex-1 overflow-y-auto bg-background flex flex-col">
                <div class="flex-1 p-6 lg:p-8">
                    {{ $slot }}
                </div>

                {{-- Footer --}}
                <footer class="mt-auto border-t border-surface-container-high bg-white px-6 lg:px-8 py-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 text-xs text-on-surface-variant">

                        {{-- Left: branding + copyright --}}
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('images/hali-logo.png') }}" alt="HALI Access" class="h-5 w-auto opacity-70">
                            <span class="text-outline-variant">|</span>
                            <span>&copy; {{ date('Y') }} HALI Access Network. All rights reserved.</span>
                        </div>

                        {{-- Center: support links --}}
                        <div class="flex items-center gap-4">
                            <a href="mailto:support@haliaccess.org"
                               class="flex items-center gap-1.5 hover:text-primary transition-colors">
                                <i class="fa-regular fa-envelope text-[11px]"></i>
                                Support
                            </a>
                            <a href="#"
                               class="flex items-center gap-1.5 hover:text-primary transition-colors">
                                <i class="fa-regular fa-circle-question text-[11px]"></i>
                                Help Center
                            </a>
                            <a href="#"
                               class="flex items-center gap-1.5 hover:text-primary transition-colors">
                                <i class="fa-solid fa-shield-halved text-[11px]"></i>
                                Privacy
                            </a>
                        </div>

                        {{-- Right: social handles --}}
                        <div class="flex items-center gap-3">
                            <a href="https://linkedin.com/company/haliaccess" target="_blank" rel="noopener"
                               class="w-7 h-7 rounded-full bg-surface-container flex items-center justify-center hover:bg-primary hover:text-white transition-all text-[11px]"
                               title="LinkedIn">
                                <i class="fa-brands fa-linkedin-in"></i>
                            </a>
                            <a href="https://twitter.com/haliaccess" target="_blank" rel="noopener"
                               class="w-7 h-7 rounded-full bg-surface-container flex items-center justify-center hover:bg-primary hover:text-white transition-all text-[11px]"
                               title="X / Twitter">
                                <i class="fa-brands fa-x-twitter"></i>
                            </a>
                            <a href="https://instagram.com/haliaccess" target="_blank" rel="noopener"
                               class="w-7 h-7 rounded-full bg-surface-container flex items-center justify-center hover:bg-primary hover:text-white transition-all text-[11px]"
                               title="Instagram">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        </div>
                    </div>
                </footer>
            </main>
        </div>
    </div>

    @livewireScripts

    {{-- ── Toast notification container ───────────────────────────────────────── --}}
    <div x-data="toastManager()"
         class="fixed top-4 right-4 z-[9999] flex flex-col gap-3 w-80 max-w-[calc(100vw-2rem)] pointer-events-none">

        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.show"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-8 scale-95"
                 x-transition:enter-end="opacity-100 translate-x-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-x-8 scale-95"
                 class="pointer-events-auto rounded-2xl shadow-2xl overflow-hidden bg-white border"
                 :class="{
                     'border-green-200': toast.type === 'success',
                     'border-red-200':   toast.type === 'error',
                     'border-amber-200': toast.type === 'warning',
                     'border-blue-200':  toast.type === 'info',
                 }">

                <div class="flex items-start gap-3 p-4">
                    {{-- Icon chip --}}
                    <div class="flex-shrink-0 w-8 h-8 rounded-xl flex items-center justify-center"
                         :class="{
                             'bg-green-100': toast.type === 'success',
                             'bg-red-100':   toast.type === 'error',
                             'bg-amber-100': toast.type === 'warning',
                             'bg-blue-100':  toast.type === 'info',
                         }">
                        <i class="fa-solid text-sm"
                           :class="{
                               'fa-circle-check text-green-600':        toast.type === 'success',
                               'fa-circle-exclamation text-red-600':    toast.type === 'error',
                               'fa-triangle-exclamation text-amber-600':toast.type === 'warning',
                               'fa-circle-info text-blue-600':          toast.type === 'info',
                           }"></i>
                    </div>

                    {{-- Message --}}
                    <div class="flex-1 min-w-0 pt-0.5">
                        <p x-show="toast.title" x-text="toast.title"
                           class="text-sm font-bold text-gray-900 mb-0.5 leading-tight"></p>
                        <p x-text="toast.message"
                           class="text-sm text-gray-600 leading-snug"></p>
                    </div>

                    {{-- Dismiss --}}
                    <button @click="dismiss(toast.id)"
                            class="flex-shrink-0 text-gray-300 hover:text-gray-500 transition-colors mt-0.5 -mr-1">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                {{-- Progress bar --}}
                <div class="h-0.5 bg-gray-100">
                    <div class="h-full transition-none"
                         :style="'width:' + toast.progress + '%'"
                         :class="{
                             'bg-green-400': toast.type === 'success',
                             'bg-red-400':   toast.type === 'error',
                             'bg-amber-400': toast.type === 'warning',
                             'bg-blue-400':  toast.type === 'info',
                         }"></div>
                </div>
            </div>
        </template>
    </div>

    {{-- Session flash → toast bridge (fires after Alpine boots) --}}
    @if(session('success'))
        <script>
            document.addEventListener('alpine:init', () =>
                window.toast('success', @js(session('success')))
            );
        </script>
    @endif
    @if(session('error'))
        <script>
            document.addEventListener('alpine:init', () =>
                window.toast('error', @js(session('error')))
            );
        </script>
    @endif
    @if(session('warning'))
        <script>
            document.addEventListener('alpine:init', () =>
                window.toast('warning', @js(session('warning')))
            );
        </script>
    @endif
    @if(session('info'))
        <script>
            document.addEventListener('alpine:init', () =>
                window.toast('info', @js(session('info')))
            );
        </script>
    @endif
</body>
</html>
