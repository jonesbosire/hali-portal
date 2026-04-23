<x-app-layout :title="($tier ? 'Edit Tier' : 'New Tier') . ' — Admin'">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.tiers.index') }}"
           class="w-8 h-8 flex items-center justify-center rounded-lg border border-surface-container-high hover:bg-surface-container-low text-on-surface-variant transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i>
        </a>
        <div>
            <h1 class="font-sans text-xl font-bold text-on-surface">{{ $tier ? 'Edit Tier' : 'New Membership Tier' }}</h1>
            <p class="font-sans text-sm text-on-surface-variant mt-0.5">
                {{ $tier ? "Editing \"{$tier->name}\"" : 'Define pricing and features for a new tier.' }}
            </p>
        </div>
    </div>

    <div class="max-w-2xl" x-data="tierForm()">

        <form @submit.prevent="save" class="space-y-6">

            {{-- Basic info --}}
            <div class="bg-white rounded-2xl border border-surface-container-high shadow-card p-6 space-y-4">
                <h2 class="font-sans text-sm font-semibold text-on-surface mb-1">Tier Details</h2>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="font-sans block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Tier Name *</label>
                        <input type="text" x-model="form.name" required maxlength="100"
                               placeholder="e.g. Member Tier 1"
                               :class="errors.name ? 'border-red-400' : 'border-outline-variant'"
                               class="font-sans w-full text-sm border rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                        <p x-show="errors.name" x-text="errors.name?.[0]" class="font-sans mt-1 text-xs text-red-500"></p>
                    </div>

                    <div>
                        <label class="font-sans block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Tier Type *</label>
                        <select x-model="form.tier_type"
                                class="font-sans w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                            <option value="member">Member</option>
                            <option value="friend">Friend of HALI</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="font-sans block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Description <span class="normal-case text-on-surface-variant/60">(optional)</span></label>
                    <textarea x-model="form.description" rows="2" maxlength="500"
                              placeholder="Brief description shown to members during checkout"
                              class="font-sans w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white resize-none"></textarea>
                </div>
            </div>

            {{-- Pricing --}}
            <div class="bg-white rounded-2xl border border-surface-container-high shadow-card p-6 space-y-4">
                <h2 class="font-sans text-sm font-semibold text-on-surface mb-1">Pricing</h2>

                <div class="grid sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2">
                        <label class="font-sans block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Price (USD) *</label>
                        <div class="relative">
                            <span class="font-sans absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">$</span>
                            <input type="number" x-model="form.price_usd" required min="0" step="1"
                                   placeholder="500"
                                   :class="errors.price_usd ? 'border-red-400' : 'border-outline-variant'"
                                   class="font-sans w-full text-sm border rounded-xl pl-7 pr-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                        </div>
                        <p x-show="errors.price_usd" x-text="errors.price_usd?.[0]" class="font-sans mt-1 text-xs text-red-500"></p>
                    </div>

                    <div>
                        <label class="font-sans block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Cycle *</label>
                        <select x-model="form.billing_cycle"
                                class="font-sans w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                            <option value="annual">Annual</option>
                            <option value="monthly">Monthly</option>
                            <option value="one_time">One-time</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="font-sans block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Max Users <span class="normal-case text-on-surface-variant/60">(optional — leave blank for unlimited)</span></label>
                    <input type="number" x-model="form.max_users" min="1"
                           placeholder="Unlimited"
                           class="font-sans w-32 text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                </div>
            </div>

            {{-- Features --}}
            <div class="bg-white rounded-2xl border border-surface-container-high shadow-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-sans text-sm font-semibold text-on-surface">Features <span class="font-normal text-on-surface-variant">(shown to members at checkout)</span></h2>
                    <button type="button" @click="addFeature"
                            class="font-sans text-xs text-primary hover:text-primary/80 font-semibold flex items-center gap-1 transition-colors">
                        <i class="fa-solid fa-plus text-[10px]"></i> Add feature
                    </button>
                </div>

                <div class="space-y-2">
                    <template x-for="(feature, i) in form.features" :key="i">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-check text-[11px] text-primary flex-shrink-0"></i>
                            <input type="text" x-model="form.features[i]" maxlength="200"
                                   placeholder="e.g. Access to member directory"
                                   class="font-sans flex-1 text-sm border border-outline-variant rounded-xl px-3 py-2 focus:ring-2 focus:ring-primary/20 bg-white">
                            <button type="button" @click="form.features.splice(i, 1)"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-red-50 hover:text-red-500 text-on-surface-variant transition-colors flex-shrink-0">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </div>
                    </template>

                    <p x-show="form.features.length === 0" class="font-sans text-sm text-on-surface-variant/60 py-2">
                        No features added — click "Add feature" to start
                    </p>
                </div>
            </div>

            {{-- Display settings --}}
            <div class="bg-white rounded-2xl border border-surface-container-high shadow-card p-6 space-y-4">
                <h2 class="font-sans text-sm font-semibold text-on-surface mb-1">Display Settings</h2>

                <div class="flex items-center gap-4">
                    <div class="w-24">
                        <label class="font-sans block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Sort Order</label>
                        <input type="number" x-model="form.display_order" min="0" placeholder="0"
                               class="font-sans w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                    </div>

                    <div class="flex items-center gap-2 mt-5">
                        <button type="button"
                                @click="form.is_active = !form.is_active"
                                :class="form.is_active ? 'bg-primary' : 'bg-surface-container-high'"
                                class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors focus:outline-none">
                            <span :class="form.is_active ? 'translate-x-4' : 'translate-x-0.5'"
                                  class="inline-block h-4 w-4 rounded-full bg-white transition-transform shadow-sm"></span>
                        </button>
                        <span class="font-sans text-sm text-on-surface" x-text="form.is_active ? 'Active (visible to Secretariat)' : 'Inactive (hidden)'"></span>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.tiers.index') }}"
                   class="font-sans text-sm px-4 py-2.5 rounded-xl border border-outline-variant hover:bg-surface-container-low transition-colors text-on-surface">
                    Cancel
                </a>
                <button type="submit" :disabled="saving"
                        class="font-sans inline-flex items-center gap-2 bg-primary hover:bg-primary/90 disabled:opacity-60 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition-colors">
                    <i class="fa-solid text-xs" :class="saving ? 'fa-spinner fa-spin' : 'fa-floppy-disk'"></i>
                    <span x-text="saving ? 'Saving…' : '{{ $tier ? 'Save Changes' : 'Create Tier' }}'"></span>
                </button>
            </div>

        </form>
    </div>

    <script>
    function tierForm() {
        return {
            saving: false,
            errors: {},
            form: {
                name:          @json($tier?->name ?? ''),
                tier_type:     @json($tier?->tier_type ?? 'member'),
                description:   @json($tier?->description ?? ''),
                price_usd:     @json($tier?->price_usd ?? ''),
                billing_cycle: @json($tier?->billing_cycle ?? 'annual'),
                features:      @json($tier?->features ?? []),
                max_users:     @json($tier?->max_users ?? ''),
                is_active:     @json($tier?->is_active ?? true),
                display_order: @json($tier?->display_order ?? 0),
            },

            addFeature() {
                this.form.features.push('');
                this.$nextTick(() => {
                    const inputs = this.$el.querySelectorAll('[x-model^="form.features"]');
                    inputs[inputs.length - 1]?.focus();
                });
            },

            async save() {
                this.saving = true;
                this.errors = {};

                const url   = @json($tier ? route('admin.tiers.update', $tier) : route('admin.tiers.store'));
                const method = @json($tier ? 'PUT' : 'POST');

                try {
                    const res = await fetch(url, {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept':       'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify(this.form),
                    });

                    const data = await res.json();

                    if (!res.ok) {
                        if (data.errors) this.errors = data.errors;
                        window.toast('error', data.message ?? 'Please fix the errors and try again.');
                        return;
                    }

                    window.toast('success', @json($tier ? 'Tier updated.' : 'Tier created.'));
                    setTimeout(() => window.location.href = '{{ route('admin.tiers.index') }}', 600);

                } catch {
                    window.toast('error', 'Network error. Please try again.');
                } finally {
                    this.saving = false;
                }
            },
        };
    }
    </script>

</x-app-layout>
