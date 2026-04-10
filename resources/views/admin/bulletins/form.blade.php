<x-app-layout title="{{ isset($bulletin) ? 'Edit Bulletin' : 'Compose Bulletin' }} — Admin">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.bulletins.index') }}" class="text-hali-text-secondary hover:text-primary text-sm">← Bulletins</a>
        <span class="text-gray-300">/</span>
        <h1 class="text-xl font-bold text-hali-text-primary">{{ isset($bulletin) ? 'Edit Bulletin' : 'Compose Bulletin' }}</h1>
    </div>

    <form method="POST"
          action="{{ isset($bulletin) ? route('admin.bulletins.update', $bulletin) : route('admin.bulletins.store') }}"
          class="space-y-5 max-w-3xl">
        @csrf
        @if(isset($bulletin)) @method('PATCH') @endif

        <div class="bg-white rounded-2xl border border-hali-border shadow-card p-6 space-y-4">
            <h2 class="text-sm font-semibold text-hali-text-primary">Bulletin Details</h2>

            <div>
                <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Subject Line *</label>
                <input type="text" name="subject" value="{{ old('subject', $bulletin->subject ?? '') }}" required
                       placeholder="e.g. HALI Network Update — March 2026"
                       class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary @error('subject') border-red-400 @enderror">
                @error('subject') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Audience</label>
                <select name="audience" class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                    <option value="all" {{ old('audience', $bulletin->audience ?? 'all') === 'all' ? 'selected' : '' }}>All Members</option>
                    <option value="member" {{ old('audience', $bulletin->audience ?? '') === 'member' ? 'selected' : '' }}>Members Only</option>
                    <option value="secretariat" {{ old('audience', $bulletin->audience ?? '') === 'secretariat' ? 'selected' : '' }}>Secretariat Only</option>
                    <option value="friend" {{ old('audience', $bulletin->audience ?? '') === 'friend' ? 'selected' : '' }}>Friends Only</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Message Body *</label>
                <textarea name="body" rows="18" required
                          placeholder="Write your bulletin content here. HTML is supported."
                          class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary font-mono @error('body') border-red-400 @enderror">{{ old('body', $bulletin->body ?? '') }}</textarea>
                @error('body') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800">
            <strong>Note:</strong> Saving as draft does not send the bulletin. Use "Send Now" from the bulletins list to dispatch emails to all recipients.
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-accent hover:bg-accent-dark text-white font-semibold px-6 py-2.5 rounded-lg transition-colors text-sm">
                Save Draft
            </button>
            <a href="{{ route('admin.bulletins.index') }}" class="px-6 py-2.5 rounded-lg border border-hali-border text-sm text-hali-text-secondary hover:bg-gray-50 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</x-app-layout>
