<x-app-layout title="{{ isset($event) ? 'Edit Event' : 'Create Event' }} — Admin">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.events.index') }}" class="text-hali-text-secondary hover:text-primary text-sm">← Events</a>
        <span class="text-gray-300">/</span>
        <h1 class="text-xl font-bold text-hali-text-primary">{{ isset($event) ? 'Edit Event' : 'Create Event' }}</h1>
    </div>

    <form method="POST"
          action="{{ isset($event) ? route('admin.events.update', $event) : route('admin.events.store') }}"
          enctype="multipart/form-data"
          class="space-y-5 max-w-3xl">
        @csrf
        @if(isset($event)) @method('PATCH') @endif

        {{-- Basic Info --}}
        <div class="bg-white rounded-2xl border border-hali-border shadow-card p-6 space-y-4">
            <h2 class="text-sm font-semibold text-hali-text-primary">Event Details</h2>

            <div>
                <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Title *</label>
                <input type="text" name="title" value="{{ old('title', $event->title ?? '') }}" required
                       class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary @error('title') border-red-400 @enderror">
                @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Type *</label>
                    <select name="type" required class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                        @foreach(['webinar','conference','workshop','indaba','other'] as $t)
                            <option value="{{ $t }}" {{ old('type', $event->type ?? '') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Status</label>
                    <select name="status" class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                        @foreach(['draft','published','canceled'] as $s)
                            <option value="{{ $s }}" {{ old('status', $event->status ?? 'draft') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Description</label>
                <textarea name="description" rows="5"
                          class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">{{ old('description', $event->description ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Start Date & Time *</label>
                    <input type="datetime-local" name="start_datetime"
                           value="{{ old('start_datetime', isset($event) ? $event->start_datetime->format('Y-m-d\TH:i') : '') }}" required
                           class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-hali-text-primary mb-1.5">End Date & Time</label>
                    <input type="datetime-local" name="end_datetime"
                           value="{{ old('end_datetime', isset($event) ? $event->end_datetime?->format('Y-m-d\TH:i') : '') }}"
                           class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Registration Closes</label>
                    <input type="datetime-local" name="registration_closes_at"
                           value="{{ old('registration_closes_at', isset($event) ? $event->registration_closes_at?->format('Y-m-d\TH:i') : '') }}"
                           class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Max Attendees</label>
                    <input type="number" name="max_attendees" min="1"
                           value="{{ old('max_attendees', $event->max_attendees ?? '') }}"
                           placeholder="Unlimited"
                           class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                </div>
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $event->is_featured ?? false) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-primary focus:ring-primary">
                    Featured event
                </label>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_members_only" value="1" {{ old('is_members_only', $event->is_members_only ?? false) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-primary focus:ring-primary">
                    Members only
                </label>
            </div>
        </div>

        {{-- Location --}}
        <div class="bg-white rounded-2xl border border-hali-border shadow-card p-6 space-y-4">
            <h2 class="text-sm font-semibold text-hali-text-primary">Location</h2>

            <div>
                <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Location Type</label>
                <select name="location_type" class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                    <option value="virtual" {{ old('location_type', $event->location_type ?? '') === 'virtual' ? 'selected' : '' }}>Virtual</option>
                    <option value="in_person" {{ old('location_type', $event->location_type ?? '') === 'in_person' ? 'selected' : '' }}>In Person</option>
                    <option value="hybrid" {{ old('location_type', $event->location_type ?? '') === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Venue Name</label>
                    <input type="text" name="venue_name" value="{{ old('venue_name', $event->venue_name ?? '') }}"
                           class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Venue Address</label>
                    <input type="text" name="venue_address" value="{{ old('venue_address', $event->venue_address ?? '') }}"
                           class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Virtual Link</label>
                    <input type="url" name="virtual_link" value="{{ old('virtual_link', $event->virtual_link ?? '') }}"
                           placeholder="https://zoom.us/j/..."
                           class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-accent hover:bg-accent-dark text-white font-semibold px-6 py-2.5 rounded-lg transition-colors text-sm">
                {{ isset($event) ? 'Update Event' : 'Create Event' }}
            </button>
            <a href="{{ route('admin.events.index') }}" class="px-6 py-2.5 rounded-lg border border-hali-border text-sm text-hali-text-secondary hover:bg-gray-50 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</x-app-layout>
