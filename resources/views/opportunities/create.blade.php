<x-app-layout title="Post an Opportunity">
    <div class="max-w-2xl">
        <nav class="text-xs text-hali-text-secondary mb-4 flex items-center gap-1.5">
            <a href="{{ route('opportunities.index') }}" class="hover:text-primary">Opportunities</a>
            <span>/</span>
            <span>Post New</span>
        </nav>

        <div class="bg-white rounded-2xl border border-hali-border shadow-card p-6">
            <h1 class="text-xl font-bold text-hali-text-primary mb-5">Post an Opportunity</h1>

            <form method="POST" action="{{ route('opportunities.store') }}" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary @error('title') border-red-400 @enderror">
                        @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Type *</label>
                        <select name="type" required class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                            @foreach(['job','fellowship','scholarship','internship','volunteer'] as $t)
                                <option value="{{ $t }}" {{ old('type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Organization</label>
                        <select name="organization_id" class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                            <option value="">None / Individual</option>
                            @foreach($organizations as $org)
                                <option value="{{ $org->id }}" {{ old('organization_id') === $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Location</label>
                        <input type="text" name="location" value="{{ old('location') }}" placeholder="Nairobi, Kenya / Remote"
                               class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Salary / Stipend</label>
                        <input type="text" name="salary_range" value="{{ old('salary_range') }}" placeholder="e.g. KES 80K–120K / month"
                               class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Application Deadline</label>
                        <input type="date" name="deadline_at" value="{{ old('deadline_at') }}"
                               class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Application URL</label>
                        <input type="url" name="application_url" value="{{ old('application_url') }}" placeholder="https://..."
                               class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Description *</label>
                        <textarea name="description" rows="5" required
                                  class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-hali-text-primary mb-1.5">Requirements</label>
                        <textarea name="requirements" rows="3"
                                  class="w-full text-sm border border-hali-border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary">{{ old('requirements') }}</textarea>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_members_only" value="1" {{ old('is_members_only') ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-primary focus:ring-primary">
                            <span class="text-sm text-hali-text-secondary">Visible to members only</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="bg-accent hover:bg-accent-dark text-white font-semibold px-6 py-2.5 rounded-lg transition-colors text-sm">
                        Post Opportunity
                    </button>
                    <a href="{{ route('opportunities.index') }}" class="px-6 py-2.5 border border-hali-border rounded-lg text-sm text-hali-text-secondary hover:bg-gray-50 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
