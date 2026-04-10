<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Portal' }} — HALI Access Network</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    {{ $head ?? '' }}
</head>
<body class="h-full font-sans antialiased bg-hali-bg"
      x-data="{ sidebarOpen: false }"
      x-cloak>

    <div class="flex h-screen overflow-hidden">

        {{-- Sidebar (desktop always visible, mobile via x-show) --}}
        <div class="hidden lg:flex lg:flex-shrink-0">
            @include('partials.sidebar')
        </div>

        {{-- Mobile sidebar --}}
        <div x-show="sidebarOpen" class="fixed inset-0 z-40 flex lg:hidden" style="display:none">
            <div x-show="sidebarOpen"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false"
                 class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
            <div class="relative flex-1 flex flex-col max-w-xs w-full">
                @include('partials.sidebar')
            </div>
        </div>

        {{-- Main area --}}
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

            @include('partials.topbar')

            <main class="flex-1 overflow-y-auto">
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                         class="mx-6 mt-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded-lg text-sm flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            {{ session('success') }}
                        </div>
                        <button @click="show = false" class="text-green-400 hover:text-green-600 ml-4 text-lg leading-none">&times;</button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)"
                         class="mx-6 mt-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded-lg text-sm flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            {{ session('error') }}
                        </div>
                        <button @click="show = false" class="text-red-400 hover:text-red-600 ml-4 text-lg leading-none">&times;</button>
                    </div>
                @endif

                @if(auth()->user()?->primaryOrganization()?->subscription?->isPastDue())
                    <div class="mx-6 mt-4 p-3 bg-yellow-50 border border-yellow-300 text-yellow-800 rounded-lg text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-yellow-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <span>Your membership subscription is past due. <a href="{{ route('billing.index') }}" class="font-semibold underline hover:no-underline">Update billing</a> to maintain access.</span>
                    </div>
                @endif

                <div class="p-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
