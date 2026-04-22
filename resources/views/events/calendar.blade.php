<x-app-layout title="Events — Calendar">
    @php
        $dayLabels  = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        // Day-of-week the 1st falls on (0=Mon … 6=Sun, ISO)
        $firstDow   = (int) $start->copy()->startOfMonth()->isoFormat('E') - 1; // 0-indexed
        $daysInMonth = (int) $start->daysInMonth;
        $today       = now()->day;
        $isThisMonth = (now()->year === $year && now()->month === $month);
    @endphp

    {{-- ── Header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-headline font-bold text-on-surface">Events Calendar</h1>
            <p class="text-sm text-on-surface-variant mt-0.5">Browse events by date</p>
        </div>
        <div class="flex items-center gap-2">
            {{-- View toggle --}}
            <a href="{{ route('events.index') }}"
               class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-semibold text-on-surface-variant hover:bg-surface-container-low transition-colors">
                <i class="fa-solid fa-grip text-[14px]"></i> List
            </a>
            <span class="px-3 py-2 rounded-xl text-sm font-semibold bg-primary text-white flex items-center gap-1.5">
                <i class="fa-solid fa-calendar-days text-[14px]"></i> Calendar
            </span>
        </div>
    </div>

    {{-- ── Month navigation ── --}}
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/20 overflow-hidden shadow-card">

        <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant/20">
            <a href="{{ route('events.index', ['view' => 'calendar', 'year' => $prevMonth->year, 'month' => $prevMonth->month]) }}"
               class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-surface-container-low text-on-surface-variant hover:text-primary transition-colors">
                <i class="fa-solid fa-chevron-left text-sm"></i>
            </a>

            <h2 class="text-lg font-headline font-bold text-on-surface">
                {{ $start->format('F Y') }}
            </h2>

            <a href="{{ route('events.index', ['view' => 'calendar', 'year' => $nextMonth->year, 'month' => $nextMonth->month]) }}"
               class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-surface-container-low text-on-surface-variant hover:text-primary transition-colors">
                <i class="fa-solid fa-chevron-right text-sm"></i>
            </a>
        </div>

        {{-- ── Day-of-week header ── --}}
        <div class="grid grid-cols-7 border-b border-outline-variant/20">
            @foreach($dayLabels as $label)
                <div class="py-3 text-center text-xs font-bold text-on-surface-variant uppercase tracking-wide">
                    {{ $label }}
                </div>
            @endforeach
        </div>

        {{-- ── Day cells ── --}}
        <div class="grid grid-cols-7 divide-x divide-y divide-outline-variant/10">

            {{-- Leading empty cells for the first week --}}
            @for($pad = 0; $pad < $firstDow; $pad++)
                <div class="min-h-[100px] bg-surface-container/30 p-2"></div>
            @endfor

            @for($day = 1; $day <= $daysInMonth; $day++)
                @php
                    $isToday   = $isThisMonth && $day === $today;
                    $dayEvents = $eventsByDay->get($day, collect());
                @endphp
                <div class="min-h-[100px] p-2 flex flex-col {{ $isToday ? 'bg-primary/5' : 'hover:bg-surface-container-low/50' }} transition-colors">

                    {{-- Day number --}}
                    <div class="mb-1">
                        <span class="inline-flex w-7 h-7 items-center justify-center rounded-full text-sm font-semibold
                            {{ $isToday ? 'bg-primary text-white' : 'text-on-surface-variant' }}">
                            {{ $day }}
                        </span>
                    </div>

                    {{-- Events for this day --}}
                    <div class="space-y-1 flex-1">
                        @foreach($dayEvents->take(3) as $event)
                            @php
                                $isRegistered = $myRegistrations->has($event->id);
                                $dotColor = match($event->type) {
                                    'indaba'     => 'bg-accent-DEFAULT',
                                    'webinar'    => 'bg-primary',
                                    'conference' => 'bg-purple-500',
                                    'workshop'   => 'bg-green-500',
                                    default      => 'bg-gray-400',
                                };
                            @endphp
                            <a href="{{ route('events.show', $event) }}"
                               title="{{ $event->title }}"
                               class="flex items-center gap-1.5 px-1.5 py-0.5 rounded-md text-[10px] font-semibold leading-snug truncate
                                      {{ $isRegistered ? 'bg-green-100 text-green-800' : 'bg-surface-container text-on-surface' }}
                                      hover:opacity-80 transition-opacity group">
                                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 {{ $dotColor }}"></span>
                                <span class="truncate">{{ $event->title }}</span>
                            </a>
                        @endforeach
                        @if($dayEvents->count() > 3)
                            @php
                                $extra     = $dayEvents->count() - 3;
                                $firstDate = $start->copy()->setDay($day)->format('Y-m-d');
                            @endphp
                            <a href="{{ route('events.index', ['date' => $firstDate]) }}"
                               class="text-[10px] text-primary font-bold hover:underline px-1">
                                +{{ $extra }} more
                            </a>
                        @endif
                    </div>
                </div>
            @endfor

            {{-- Trailing empty cells to fill the last row --}}
            @php
                $total     = $firstDow + $daysInMonth;
                $remainder = $total % 7;
                $trailing  = $remainder > 0 ? 7 - $remainder : 0;
            @endphp
            @for($t = 0; $t < $trailing; $t++)
                <div class="min-h-[100px] bg-surface-container/30 p-2"></div>
            @endfor

        </div>
    </div>

    {{-- ── Legend ── --}}
    <div class="mt-4 flex flex-wrap items-center gap-4 text-xs text-on-surface-variant">
        <span class="font-semibold">Event types:</span>
        @foreach(['indaba' => ['bg-accent-DEFAULT','Indaba'], 'webinar' => ['bg-primary','Webinar'], 'conference' => ['bg-purple-500','Conference'], 'workshop' => ['bg-green-500','Workshop'], 'other' => ['bg-gray-400','Other']] as $type => [$color, $label])
            <span class="flex items-center gap-1.5">
                <span class="w-2.5 h-2.5 rounded-full {{ $color }}"></span>
                {{ $label }}
            </span>
        @endforeach
        <span class="flex items-center gap-1.5 ml-4">
            <span class="w-2.5 h-2.5 rounded bg-green-100 border border-green-300"></span>
            You're registered
        </span>
    </div>

    {{-- ── Jump to today ── --}}
    @if(!$isThisMonth)
        <div class="mt-3 text-center">
            <a href="{{ route('events.index', ['view' => 'calendar']) }}"
               class="text-sm text-primary font-semibold hover:underline">
                ← Back to {{ now()->format('F Y') }}
            </a>
        </div>
    @endif

</x-app-layout>
