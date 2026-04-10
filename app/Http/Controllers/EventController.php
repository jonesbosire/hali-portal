<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::published()
            ->with(['creator', 'registrations']);

        // Filters
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

    public function show(Event $event)
    {
        abort_if($event->status !== 'published', 404);

        $userRegistration = auth()->user()
            ->eventRegistrations()
            ->where('event_id', $event->id)
            ->first();

        $event->load('registrations.user', 'creator');
        $attendeeCount = $event->attendees()->count();

        return view('events.show', compact('event', 'userRegistration', 'attendeeCount'));
    }

    public function register(Request $request, Event $event)
    {
        abort_if(!$event->isRegistrationOpen(), 422, 'Registration is not open for this event.');
        abort_if($event->isFull(), 422, 'This event is full.');

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

        EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'organization_id' => auth()->user()->primaryOrganization()?->id,
            'status' => 'registered',
            'dietary_requirements' => $request->dietary_requirements,
            'registration_notes' => $request->registration_notes,
            'registered_at' => now(),
        ]);

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
