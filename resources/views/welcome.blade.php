<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HALI Access Partner Portal</title>
    <meta name="description" content="The HALI Access Partner Portal connects the organisations working to get African students into university and support them to succeed.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300..700;1,9..40,300..700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body text-[#1a0a00] bg-white">

    {{-- ── Nav ── --}}
    <header class="border-b border-[#edd5be] bg-white sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="/">
                <img src="{{ asset('images/hali-logo.png') }}" alt="HALI Access Network" class="h-9 w-auto">
            </a>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="text-sm font-semibold text-[#7c3d1f] hover:underline">Sign in</a>
                <a href="mailto:portal@haliaccess.org"
                   class="text-sm font-semibold bg-[#7c3d1f] text-white px-4 py-2 rounded-xl hover:bg-[#6b3218] transition-colors">
                    Request access
                </a>
            </div>
        </div>
    </header>

    {{-- ── Hero ── --}}
    <section class="max-w-6xl mx-auto px-6 pt-20 pb-16">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold text-[#cc9933] uppercase tracking-wider mb-4">For HALI Access partner organisations</p>
            <h1 class="font-headline text-5xl md:text-6xl font-bold text-[#1a0a00] leading-tight mb-6">
                One place to manage your work with HALI Access
            </h1>
            <p class="text-xl text-[#52433a] leading-relaxed mb-8 max-w-2xl">
                The Partner Portal is where the organisations in the HALI Access network connect, share resources, register for events, and stay updated on what is happening across the programme.
            </p>
            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center gap-2 bg-[#7c3d1f] text-white font-semibold px-6 py-3.5 rounded-xl hover:bg-[#6b3218] transition-colors text-base">
                    Sign in to the portal
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                <a href="mailto:portal@haliaccess.org"
                   class="inline-flex items-center gap-2 text-[#7c3d1f] font-semibold px-6 py-3.5 rounded-xl border border-[#edd5be] hover:bg-[#fdf6ef] transition-colors text-base">
                    Request access
                </a>
            </div>
        </div>
    </section>

    {{-- ── Product screenshot ── --}}
    <section class="max-w-6xl mx-auto px-6 pb-24">
        <div class="rounded-2xl overflow-hidden border border-[#edd5be] shadow-2xl shadow-black/10">
            {{-- Fake browser chrome so it looks like a real product --}}
            <div class="bg-[#f5e8d8] border-b border-[#edd5be] px-4 py-3 flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-[#d6b89a]"></span>
                <span class="w-3 h-3 rounded-full bg-[#d6b89a]"></span>
                <span class="w-3 h-3 rounded-full bg-[#d6b89a]"></span>
                <div class="ml-4 flex-1 bg-white/60 rounded-lg px-3 py-1 text-xs text-[#9d7060] max-w-xs">
                    portal.haliaccess.org/dashboard
                </div>
            </div>
            @if(file_exists(public_path('images/dashboard-screenshot.png')))
                <img src="{{ asset('images/dashboard-screenshot.png') }}"
                     alt="HALI Access Partner Portal dashboard"
                     class="w-full block">
            @else
                {{-- Placeholder — replace by saving a screenshot to public/images/dashboard-screenshot.png --}}
                <div class="bg-[#fdf6ef] min-h-[480px] flex flex-col items-center justify-center gap-4 px-8 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-[#edd5be] flex items-center justify-center">
                        <svg class="w-8 h-8 text-[#9d7060]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[#5c3520] font-semibold mb-1">Add a dashboard screenshot here</p>
                        <p class="text-sm text-[#9d7060]">Save it as <code class="bg-[#edd5be] px-1.5 py-0.5 rounded text-xs font-mono">public/images/dashboard-screenshot.png</code></p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- ── What's in the portal ── --}}
    <section class="bg-[#fdf6ef] border-y border-[#edd5be]">
        <div class="max-w-6xl mx-auto px-6 py-20">
            <div class="max-w-xl mb-12">
                <h2 class="font-headline text-3xl md:text-4xl font-bold text-[#1a0a00] mb-4">
                    Everything your organisation needs in one place
                </h2>
                <p class="text-[#52433a] text-lg leading-relaxed">
                    The portal is built specifically for the organisations in the HALI Access network. Here is what you can do with it.
                </p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php $features = [
                    [
                        'icon'  => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
                        'label' => 'Member directory',
                        'desc'  => 'Find contact details and profiles for all partner organisations in the network. Useful when you want to connect with someone doing similar work.',
                    ],
                    [
                        'icon'  => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                        'label' => 'Events',
                        'desc'  => 'Register for HALI Access conferences, workshops, and webinars. You can see the programme in advance and find out who else is attending.',
                    ],
                    [
                        'icon'  => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z',
                        'label' => 'Stories and updates',
                        'desc'  => 'Read updates from the HALI Secretariat and stories from across the network. You can also post about the work your organisation is doing.',
                    ],
                    [
                        'icon'  => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
                        'label' => 'Opportunities',
                        'desc'  => 'Post and find jobs, fellowships, scholarships, and other opportunities that are relevant to the HALI network.',
                    ],
                    [
                        'icon'  => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z',
                        'label' => 'Resources',
                        'desc'  => 'Download guides, templates, and materials that the Secretariat has shared with the network.',
                    ],
                    [
                        'icon'  => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
                        'label' => 'Notifications',
                        'desc'  => 'Get notified by email and SMS when your account status changes, when you register for an event, or when the Secretariat sends an update.',
                    ],
                ]; @endphp
                @foreach($features as $f)
                    <div class="bg-white rounded-2xl border border-[#edd5be] p-6">
                        <div class="w-10 h-10 rounded-xl bg-[#fdf6ef] border border-[#edd5be] flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-[#7c3d1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $f['icon'] }}"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-[#1a0a00] mb-2">{{ $f['label'] }}</h3>
                        <p class="text-sm text-[#52433a] leading-relaxed">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ── Who is this for ── --}}
    <section class="max-w-6xl mx-auto px-6 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
            <div>
                <h2 class="font-headline text-3xl md:text-4xl font-bold text-[#1a0a00] mb-6">
                    Who uses the portal
                </h2>
                <div class="space-y-6">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl bg-[#fdf6ef] border border-[#edd5be] flex-shrink-0 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#7c3d1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-[#1a0a00] mb-1">Member organisations</h3>
                            <p class="text-sm text-[#52433a] leading-relaxed">
                                Schools, NGOs, and other organisations that are full members of HALI Access. You have access to everything in the portal.
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl bg-[#fdf6ef] border border-[#edd5be] flex-shrink-0 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#7c3d1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-[#1a0a00] mb-1">Friend organisations</h3>
                            <p class="text-sm text-[#52433a] leading-relaxed">
                                Universities and supporters who are friends of HALI Access. You can access the directory, events, and resources relevant to your relationship with the network.
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl bg-[#fdf6ef] border border-[#edd5be] flex-shrink-0 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#7c3d1f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-[#1a0a00] mb-1">The HALI Secretariat</h3>
                            <p class="text-sm text-[#52433a] leading-relaxed">
                                The HALI Access team manages members, publishes content, runs events, and administers the network through the admin area.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#fdf6ef] border border-[#edd5be] rounded-2xl p-8">
                <h3 class="font-headline font-bold text-xl text-[#1a0a00] mb-2">Want to join the network?</h3>
                <p class="text-sm text-[#52433a] leading-relaxed mb-6">
                    Access to the portal is by invitation. If your organisation works with HALI students and you think you should be in the network, get in touch.
                </p>
                <a href="mailto:portal@haliaccess.org?subject=Partner portal access request"
                   class="inline-flex items-center gap-2 bg-[#7c3d1f] text-white font-semibold px-5 py-3 rounded-xl hover:bg-[#6b3218] transition-colors text-sm">
                    Write to the Secretariat
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </a>
                <p class="mt-4 text-xs text-[#9d7060]">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-[#7c3d1f] font-semibold hover:underline">Sign in here</a>
                </p>
            </div>
        </div>
    </section>

    {{-- ── Footer ── --}}
    <footer class="border-t border-[#edd5be] bg-[#fdf6ef]">
        <div class="max-w-6xl mx-auto px-6 py-10 flex flex-col sm:flex-row items-center justify-between gap-4">
            <img src="{{ asset('images/hali-logo.png') }}" alt="HALI Access Network" class="h-8 w-auto">
            <p class="text-sm text-[#9d7060]">
                &copy; {{ date('Y') }} HALI Access Partner Network &middot;
                <a href="mailto:portal@haliaccess.org" class="hover:underline">portal@haliaccess.org</a>
            </p>
        </div>
    </footer>

</body>
</html>
