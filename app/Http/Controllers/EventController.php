<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Notifications\EventRegistrationConfirmation;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        // ── Calendar view ─────────────────────────────────────────────────────
        if ($request->get('view') === 'calendar') {
            return $this->calendarView($request);
        }

        // ── List view (default) ───────────────────────────────────────────────
        $query = Event::published()
            ->with(['creator', 'registrations']);

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($request->get('upcoming')) {
            $query->upcoming();
        } elseif ($request->get('past')) {
            $query->past();
        } else {
            $query->orderBy('start_datetime');
        }

        if ($request->get('open')) {
            $query->where('registration_opens_at', '<=', now())
                ->where(fn($q) => $q->whereNull('registration_closes_at')->orWhere('registration_closes_at', '>=', now()));
        }

        $events = $query->paginate(12)->withQueryString();

        $featuredEvent = Event::published()
            ->featured()
            ->upcoming()
            ->with('registrations')
            ->first();

        return view('events.index', compact('events', 'featuredEvent'));
    }

    private function calendarView(Request $request)
    {
        // Resolve the requested month (defaults to current)
        $year  = (int) $request->get('year',  now()->year);
        $month = (int) $request->get('month', now()->month);

        // Clamp to sane range
        $year  = max(2020, min(2040, $year));
        $month = max(1,    min(12,   $month));

        $start = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        // Previous / next month for navigation links
        $prevMonth = $start->copy()->subMonthNoOverflow();
        $nextMonth = $start->copy()->addMonthNoOverflow();

        // Fetch all published events in this month
        $monthEvents = Event::published()
            ->whereBetween('start_datetime', [$start, $end])
            ->orderBy('start_datetime')
            ->get();

        // Key by day-of-month for O(1) lookup in the blade
        $eventsByDay = $monthEvents->groupBy(fn($e) => $e->start_datetime->day);

        // Also fetch the user's registrations for quick "registered" badges
        $myRegistrations = auth()->user()
            ->eventRegistrations()
            ->whereIn('event_id', $monthEvents->pluck('id'))
            ->where('status', '!=', 'canceled')
            ->pluck('event_id')
            ->flip(); // id => index for O(1) lookup

        return view('events.calendar', compact(
            'start', 'year', 'month',
            'prevMonth', 'nextMonth',
            'eventsByDay', 'myRegistrations',
        ));
    }

    public function show(Event $event)
    {
        abort_if($event->status !== 'published', 404);

        $userRegistration = auth()->user()
            ->eventRegistrations()
            ->where('event_id', $event->id)
            ->first();

        $event->load('registrations.user', 'creator', 'programs');

        return view('events.show', compact('event', 'userRegistration'));
    }

    public function register(Request $request, Event $event)
    {
        abort_if(!$event->isRegistrationOpen(), 422, 'Registration is not open for this event.');
        abort_if($event->isFull(), 422, 'This event is full.');
        abort_if(
            $event->is_members_only && !auth()->user()->isMember() && !auth()->user()->isAdmin(),
            403,
            'This event is open to members only.'
        );

        $existing = EventRegistration::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return back()->with('error', 'You are already registered for this event.');
        }

        $request->validate([
            'dietary_requirements' => 'nullable|string|max:255',
            'registration_notes' => 'nullable|string|max:1000',
        ]);

        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'organization_id' => auth()->user()->primaryOrganization()?->id,
            'status' => 'registered',
            'dietary_requirements' => $request->dietary_requirements,
            'registration_notes' => $request->registration_notes,
            'registered_at' => now(),
        ]);

        auth()->user()->notify(new EventRegistrationConfirmation($event, $registration));

        return back()->with('success', "You're registered for {$event->title}! Check your email for confirmation.");
    }

    public function cancelRegistration(Event $event)
    {
        $registration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $registration->update([
            'status' => 'canceled',
            'canceled_at' => now(),
        ]);

        return back()->with('success', 'Your registration has been canceled.');
    }
}
