<x-app-layout title="{{ isset($opportunity) ? 'Edit Opportunity' : 'Post Opportunity' }} — Admin">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.opportunities.index') }}" class="text-hali-text-secondary hover:text-primary text-sm">← Opportunities</a>
        <span class="text-gray-300">/</span>
        <h1 class="text-xl font-bold text-hali-text-primary">{{ isset($opportunity) ? 'Edit Opportunity' : 'Post Opportunity' }}</h1>
    </div>

    <form method="POST"
          action="{{ isset($opportunity) ? route('admin.opportunities.update', $opportunity) : route('admin.opportunities.store') }}"
          class="space-y-5 max-w-3xl">
        @csrf
        @if(isset($opportunity)) @method('PATCH') @endif

        <div class="bg-white rounded-2xl border border-hali-border shadow-card p-6 space-y-4">
            <h2 class="text-sm font-semibold text-hali-text-primary">Opportunity Details</h2>

            <div>
                <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Title *</label>
                <input type="text" name="title" value="{{ old('title', $opportunity->title ?? '') }}" required
                       class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary @error('title') border-red-400 @enderror">
                @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Type *</label>
                    <select name="type" required class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                        @foreach(['job','fellowship','scholarship','grant','conference','other'] as $t)
                            <option value="{{ $t }}" {{ old('type', $opportunity->type ?? '') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Status</label>
                    <select name="status" class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                        @foreach(['active','draft','expired'] as $s)
                            <option value="{{ $s }}" {{ old('status', $opportunity->status ?? 'active') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Organization / Funder</label>
                    <input type="text" name="organization" value="{{ old('organization', $opportunity->organization ?? '') }}"
                           placeholder="e.g. Equity Group Foundation"
                           class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Location</label>
                    <input type="text" name="location" value="{{ old('location', $opportunity->location ?? '') }}"
                           placeholder="e.g. Nairobi, Kenya / Remote"
                           class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Application Deadline</label>
                    <input type="date" name="deadline_at"
                           value="{{ old('deadline_at', isset($opportunity) ? $opportunity->deadline_at?->format('Y-m-d') : '') }}"
                           class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Application URL</label>
                    <input type="url" name="apply_url" value="{{ old('apply_url', $opportunity->apply_url ?? '') }}"
                           placeholder="https://..."
                           class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Description *</label>
                <textarea name="description" rows="10" required
                          class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary @error('description') border-red-400 @enderror">{{ old('description', $opportunity->description ?? '') }}</textarea>
                @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_members_only" value="1"
                           {{ old('is_members_only', $opportunity->is_members_only ?? false) ? 'checked' : '' }}
                           class="rounded border-gray-300 text-primary focus:ring-primary">
                    Members only
                </label>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-accent hover:bg-accent-dark text-white font-semibold px-6 py-2.5 rounded-lg transition-colors text-sm">
                {{ isset($opportunity) ? 'Update Opportunity' : 'Post Opportunity' }}
            </button>
            <a href="{{ route('admin.opportunities.index') }}" class="px-6 py-2.5 rounded-lg border border-hali-border text-sm text-hali-text-secondary hover:bg-gray-50 transition-colors">
                Cancel
            </a>
        </div>
    </form>
</x-app-layout>
