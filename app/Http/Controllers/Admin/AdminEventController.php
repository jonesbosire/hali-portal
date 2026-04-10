<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::withTrashed()
            ->with(['creator', 'registrations'])
            ->orderByDesc('start_datetime')
            ->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.form', ['event' => new Event()]);
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request);
        $validated['created_by'] = auth()->id();

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('events', 'public');
        }

        $event = Event::create($validated);

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $event->load('registrations.user', 'registrations.organization', 'creator');
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.form', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $this->validate($request);

        if ($request->hasFile('cover_image')) {
            if ($event->cover_image) Storage::disk('public')->delete($event->cover_image);
            $validated['cover_image'] = $request->file('cover_image')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Event updated.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted.');
    }

    public function markAttended(Event $event, EventRegistration $registration)
    {
        $registration->update(['status' => 'attended', 'attended_at' => now()]);
        return back()->with('success', 'Attendance marked.');
    }

    public function exportAttendees(Event $event)
    {
        $registrations = $event->registrations()
            ->with(['user', 'organization'])
            ->get();

        $csv = "Name,Email,Organization,Status,Registered At,Dietary\n";
        foreach ($registrations as $r) {
            $csv .= "\"{$r->user->name}\",\"{$r->user->email}\",\"{$r->organization?->name}\",\"{$r->status}\",\"{$r->registered_at->format('Y-m-d H:i')}\",\"{$r->dietary_requirements}\"\n";
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$event->slug}-attendees.csv\"",
        ]);
    }

    private function validate(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'type' => 'required|in:webinar,conference,workshop,indaba,other',
            'start_datetime' => 'required|date',
            'end_datetime' => 'nullable|date|after:start_datetime',
            'timezone' => 'required|string',
            'location_type' => 'required|in:virtual,in_person,hybrid',
            'venue_name' => 'nullable|string|max:255',
            'venue_address' => 'nullable|string',
            'virtual_link' => 'nullable|url',
            'cover_image' => 'nullable|image|max:4096',
            'max_attendees' => 'nullable|integer|min:1',
            'is_members_only' => 'boolean',
            'is_featured' => 'boolean',
            'registration_opens_at' => 'nullable|date',
            'registration_closes_at' => 'nullable|date|after_or_equal:registration_opens_at',
            'status' => 'required|in:draft,published,canceled',
        ]);
    }
}
