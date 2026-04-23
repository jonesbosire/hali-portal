<x-app-layout title="Membership Tiers — Admin">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="font-sans text-xl font-bold text-on-surface">Membership Tiers</h1>
            <p class="font-sans text-sm text-on-surface-variant mt-0.5">Define the tier structure, pricing, and features for members and Friends of HALI.</p>
        </div>
        <a href="{{ route('admin.tiers.create') }}"
           class="font-sans inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors">
            <i class="fa-solid fa-plus text-xs"></i>
            New Tier
        </a>
    </div>

    @if(session('success'))
        <div class="font-sans mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-4 py-3">
            <i class="fa-solid fa-circle-check text-green-600"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($tiers->isEmpty())
        <div class="bg-white rounded-2xl border border-surface-container-high shadow-card p-14 text-center">
            <i class="fa-solid fa-layer-group text-4xl text-outline-variant mb-4 block"></i>
            <p class="font-sans font-semibold text-on-surface mb-1">No tiers yet</p>
            <p class="font-sans text-sm text-on-surface-variant mb-5">Create the first membership tier so Secretariat can assign it when sending invitations.</p>
            <a href="{{ route('admin.tiers.create') }}"
               class="font-sans inline-flex items-center gap-2 bg-primary text-white text-sm font-bold px-5 py-2.5 rounded-xl">
                <i class="fa-solid fa-plus text-xs"></i> Create First Tier
            </a>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($tiers as $tier)
                <div class="bg-white rounded-2xl border border-surface-container-high shadow-card p-5 flex flex-col gap-3" id="tier-card-{{ $tier->id }}">

                    {{-- Header --}}
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h2 class="font-sans font-bold text-on-surface text-base leading-tight">{{ $tier->name }}</h2>
                            <span class="font-sans text-xs mt-1 inline-block px-2 py-0.5 rounded-full font-medium
                                {{ $tier->tier_type === 'friend' ? 'bg-blue-100 text-blue-700' : 'bg-primary/10 text-primary' }}">
                                {{ $tier->tier_type === 'friend' ? 'Friend of HALI' : 'Member' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            {{-- Active toggle --}}
                            <button onclick="toggleTier('{{ $tier->id }}', this)"
                                    data-active="{{ $tier->is_active ? '1' : '0' }}"
                                    title="{{ $tier->is_active ? 'Deactivate' : 'Activate' }}"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border transition-colors
                                        {{ $tier->is_active ? 'border-green-200 bg-green-50 text-green-600 hover:bg-green-100' : 'border-surface-container-high bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }}">
                                <i class="fa-solid text-xs {{ $tier->is_active ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                            </button>
                            <a href="{{ route('admin.tiers.edit', $tier) }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg border border-surface-container-high bg-white hover:bg-surface-container-low text-on-surface-variant transition-colors">
                                <i class="fa-solid fa-pen text-xs"></i>
                            </a>
                            <button onclick="deleteTier('{{ $tier->id }}', '{{ addslashes($tier->name) }}', this)"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg border border-surface-container-high bg-white hover:bg-red-50 hover:border-red-200 text-on-surface-variant hover:text-red-500 transition-colors">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="flex items-baseline gap-1.5">
                        <span class="font-sans text-2xl font-bold text-on-surface">${{ number_format($tier->price_usd, 0) }}</span>
                        <span class="font-sans text-sm text-on-surface-variant">
                            / {{ match($tier->billing_cycle) { 'annual' => 'year', 'monthly' => 'month', default => 'one-time' } }}
                        </span>
                    </div>

                    {{-- Description --}}
                    @if($tier->description)
                        <p class="font-sans text-sm text-on-surface-variant leading-relaxed">{{ $tier->description }}</p>
                    @endif

                    {{-- Features --}}
                    @if($tier->features && count($tier->features))
                        <ul class="space-y-1.5">
                            @foreach($tier->features as $feature)
                                <li class="font-sans flex items-start gap-2 text-sm text-on-surface">
                                    <i class="fa-solid fa-check text-[11px] text-primary mt-0.5 flex-shrink-0"></i>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    {{-- Footer stats --}}
                    <div class="mt-auto pt-3 border-t border-surface-container-high flex items-center justify-between text-xs text-on-surface-variant">
                        <span>
                            <i class="fa-solid fa-users mr-1"></i>
                            {{ $tier->members()->count() }} member{{ $tier->members()->count() !== 1 ? 's' : '' }}
                        </span>
                        <span class="font-sans px-2 py-0.5 rounded-full {{ $tier->is_active ? 'bg-green-100 text-green-700' : 'bg-surface-container text-on-surface-variant' }}">
                            {{ $tier->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <script>
    async function toggleTier(id, btn) {
        btn.disabled = true;
        try {
            const res = await fetch(`/admin/tiers/${id}/toggle`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            });
            const data = await res.json();
            if (res.ok) {
                const active = data.is_active;
                btn.dataset.active = active ? '1' : '0';
                btn.title = active ? 'Deactivate' : 'Activate';
                btn.className = `w-8 h-8 flex items-center justify-center rounded-lg border transition-colors ${active ? 'border-green-200 bg-green-50 text-green-600 hover:bg-green-100' : 'border-surface-container-high bg-surface-container text-on-surface-variant hover:bg-surface-container-high'}`;
                btn.querySelector('i').className = `fa-solid text-xs ${active ? 'fa-eye' : 'fa-eye-slash'}`;

                // Update footer badge in same card
                const card = btn.closest('[id^="tier-card-"]');
                const badge = card?.querySelector('.mt-auto span:last-child');
                if (badge) {
                    badge.className = `font-sans px-2 py-0.5 rounded-full ${active ? 'bg-green-100 text-green-700' : 'bg-surface-container text-on-surface-variant'}`;
                    badge.textContent = active ? 'Active' : 'Inactive';
                }

                window.toast('success', active ? 'Tier activated.' : 'Tier deactivated.');
            } else {
                window.toast('error', 'Failed to update tier.');
            }
        } catch {
            window.toast('error', 'Network error.');
        } finally {
            btn.disabled = false;
        }
    }

    async function deleteTier(id, name, btn) {
        if (!confirm(`Delete tier "${name}"? This cannot be undone.`)) return;

        btn.disabled = true;
        try {
            const res = await fetch(`/admin/tiers/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            });
            const data = await res.json();
            if (res.ok) {
                const card = document.getElementById('tier-card-' + id);
                if (card) {
                    card.style.transition = 'opacity 0.25s';
                    card.style.opacity = '0';
                    setTimeout(() => card.remove(), 260);
                }
                window.toast('success', data.message);
            } else {
                window.toast('error', data.message ?? 'Cannot delete this tier.');
                btn.disabled = false;
            }
        } catch {
            window.toast('error', 'Network error.');
            btn.disabled = false;
        }
    }
    </script>

</x-app-layout>
