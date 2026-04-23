<x-app-layout title="Invitations — Admin">

    <div class="font-sans flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <div>
            <h1 class="font-sans text-xl font-bold text-on-surface">Member Invitations</h1>
            <p class="font-sans text-sm text-on-surface-variant mt-0.5">Invite program officers and staff to join the HALI Partner Portal.</p>
        </div>
        <div class="font-sans flex items-center gap-2 text-sm text-on-surface-variant bg-surface-container px-3 py-1.5 rounded-full">
            <i class="fa-solid fa-circle-info text-[11px] text-primary"></i>
            Invitations expire after 7 days
        </div>
    </div>

    {{-- Invite form + New Org modal — single Alpine scope --}}
    <div x-data="invitePage()">

        {{-- Send invitation form --}}
        <div class="font-sans bg-white rounded-2xl border border-surface-container-high shadow-card p-6 mb-6">

            <h2 class="font-sans text-sm font-semibold text-on-surface mb-1">Send New Invitation</h2>
            <p class="font-sans text-xs text-on-surface-variant mb-4">The recipient will receive an email with a secure link to create their account.</p>

            <form @submit.prevent="send">
                <div class="flex flex-wrap gap-3 items-end">

                    <div class="flex-1 min-w-52">
                        <label class="font-sans block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Email Address *</label>
                        <input type="email" x-model="form.email" required placeholder="officer@organization.org"
                               :class="inviteErrors.email ? 'border-red-400' : 'border-outline-variant'"
                               class="font-sans w-full text-sm border rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                        <p x-show="inviteErrors.email" x-text="inviteErrors.email?.[0]" class="font-sans mt-1 text-xs text-red-500"></p>
                    </div>

                    <div>
                        <label class="font-sans block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Role</label>
                        <select x-model="form.role" class="font-sans text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                            <option value="member">Member</option>
                            <option value="friend">Friend</option>
                            <option value="secretariat">Secretariat</option>
                        </select>
                    </div>

                    <div class="min-w-44" x-show="form.role === 'member' || form.role === 'friend'">
                        <label class="font-sans block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Membership Tier <span class="normal-case text-on-surface-variant/60">(optional)</span></label>
                        <select x-model="form.membership_tier_id" class="font-sans w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                            <option value="">— Assign later —</option>
                            <template x-for="tier in tiers" :key="tier.id">
                                <option :value="tier.id" x-text="tier.name + ' ($' + tier.price_usd + '/' + (tier.billing_cycle === 'annual' ? 'yr' : tier.billing_cycle) + ')'"></option>
                            </template>
                        </select>
                    </div>

                    <div class="min-w-48">
                        <label class="font-sans block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Organization <span class="normal-case text-on-surface-variant/60">(optional)</span></label>
                        <div class="flex items-center gap-2">
                            <select x-model="form.organization_id" class="font-sans w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                                <option value="">— None —</option>
                                <template x-for="org in orgs" :key="org.id">
                                    <option :value="org.id" x-text="org.name"></option>
                                </template>
                            </select>
                            <button type="button" @click="orgModal = true"
                                    title="Add new organization"
                                    class="font-sans flex-shrink-0 w-9 h-9 flex items-center justify-center rounded-xl border border-outline-variant bg-white hover:bg-surface-container-low transition-colors text-primary">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" :disabled="loading"
                            class="font-sans inline-flex items-center gap-2 bg-[#7c3d1f] hover:bg-[#6a3319] disabled:opacity-60 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors">
                        <i class="fa-solid text-xs" :class="loading ? 'fa-spinner fa-spin' : 'fa-paper-plane'"></i>
                        <span x-text="loading ? 'Sending…' : 'Send Invitation'"></span>
                    </button>
                </div>
            </form>
        </div>

        {{-- New Organization Modal --}}
        <div x-show="orgModal"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="orgModal = false"
             style="display:none"
             class="fixed inset-0 z-50 flex items-center justify-center p-4">

            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="orgModal = false"></div>

            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6"
                 @click.stop
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">

                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-sans font-bold text-on-surface text-base">Add New Organization</h3>
                    <button type="button" @click="orgModal = false"
                            class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-surface-container-low text-on-surface-variant transition-colors">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>

                <form @submit.prevent="saveOrg" class="space-y-4">
                    <div>
                        <label class="font-sans block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Organization Name *</label>
                        <input type="text" x-model="orgForm.name" x-ref="orgNameInput" required maxlength="255"
                               placeholder="e.g. Mastercard Foundation"
                               :class="orgErrors.name ? 'border-red-400' : 'border-outline-variant'"
                               class="font-sans w-full text-sm border rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                        <p x-show="orgErrors.name" x-text="orgErrors.name?.[0]" class="font-sans mt-1 text-xs text-red-500"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="font-sans block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Country</label>
                            <input type="text" x-model="orgForm.country" maxlength="100" placeholder="e.g. Kenya"
                                   class="font-sans w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                        </div>
                        <div>
                            <label class="font-sans block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Type</label>
                            <select x-model="orgForm.type" class="font-sans w-full text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                                <option value="member">Member Org</option>
                                <option value="friend">Friend Org</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-1">
                        <button type="button" @click="orgModal = false"
                                class="font-sans text-sm px-4 py-2.5 rounded-xl border border-outline-variant hover:bg-surface-container-low transition-colors text-on-surface">
                            Cancel
                        </button>
                        <button type="submit" :disabled="orgSaving"
                                class="font-sans inline-flex items-center gap-2 text-sm font-bold px-5 py-2.5 rounded-xl bg-[#7c3d1f] hover:bg-[#6a3319] text-white transition-colors disabled:opacity-60">
                            <i class="fa-solid text-xs" :class="orgSaving ? 'fa-spinner fa-spin' : 'fa-building'"></i>
                            <span x-text="orgSaving ? 'Creating…' : 'Create Organization'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>{{-- end x-data="invitePage()" --}}

    {{-- Stats row --}}
    @php
        $total    = $invitations->total();
        $pending  = $invitations->getCollection()->filter(fn($i) => $i->isPending())->count();
        $accepted = $invitations->getCollection()->filter(fn($i) => $i->isAccepted())->count();
        $expired  = $invitations->getCollection()->filter(fn($i) => $i->isExpired() && !$i->isAccepted())->count();
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5" id="stats-row">
        <div class="bg-white rounded-xl border border-surface-container-high p-4 text-center">
            <p class="font-sans text-2xl font-bold text-on-surface" id="stat-total">{{ $total }}</p>
            <p class="font-sans text-xs text-on-surface-variant mt-0.5">Total Sent</p>
        </div>
        <div class="bg-amber-50 rounded-xl border border-amber-100 p-4 text-center">
            <p class="font-sans text-2xl font-bold text-amber-700" id="stat-pending">{{ $pending }}</p>
            <p class="font-sans text-xs text-amber-600 mt-0.5">Pending</p>
        </div>
        <div class="bg-green-50 rounded-xl border border-green-100 p-4 text-center">
            <p class="font-sans text-2xl font-bold text-green-700" id="stat-accepted">{{ $accepted }}</p>
            <p class="font-sans text-xs text-green-600 mt-0.5">Accepted</p>
        </div>
        <div class="bg-surface-container rounded-xl border border-surface-container-high p-4 text-center">
            <p class="font-sans text-2xl font-bold text-on-surface-variant" id="stat-expired">{{ $expired }}</p>
            <p class="font-sans text-xs text-on-surface-variant mt-0.5">Expired</p>
        </div>
    </div>

    {{-- Invitations table --}}
    <div class="font-sans bg-white rounded-2xl border border-surface-container-high shadow-card overflow-hidden">
        <div class="px-5 py-3.5 border-b border-surface-container-high flex items-center justify-between">
            <p class="font-sans text-sm font-semibold text-on-surface">All Invitations</p>
            <p class="font-sans text-xs text-on-surface-variant" id="total-label">{{ $invitations->total() }} total</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm font-sans">
                <thead class="bg-surface-container/50 border-b border-surface-container-high">
                    <tr>
                        <th class="font-sans text-left text-xs font-semibold text-on-surface-variant px-5 py-3 uppercase tracking-wide">Recipient</th>
                        <th class="font-sans text-left text-xs font-semibold text-on-surface-variant px-4 py-3 uppercase tracking-wide hidden sm:table-cell">Organization</th>
                        <th class="font-sans text-left text-xs font-semibold text-on-surface-variant px-4 py-3 uppercase tracking-wide">Role</th>
                        <th class="font-sans text-left text-xs font-semibold text-on-surface-variant px-4 py-3 uppercase tracking-wide">Status</th>
                        <th class="font-sans text-left text-xs font-semibold text-on-surface-variant px-4 py-3 uppercase tracking-wide hidden md:table-cell">Sent</th>
                        <th class="font-sans text-left text-xs font-semibold text-on-surface-variant px-4 py-3 uppercase tracking-wide hidden md:table-cell">Expires</th>
                        <th class="px-4 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-high" id="invitations-tbody">
                    @forelse($invitations as $inv)
                        <tr class="hover:bg-surface-container/30 transition-colors" id="inv-row-{{ $inv->id }}">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-regular fa-envelope text-[11px] text-primary"></i>
                                    </div>
                                    <span class="font-sans font-medium text-on-surface text-sm">{{ $inv->email }}</span>
                                </div>
                            </td>
                            <td class="font-sans px-4 py-3.5 text-on-surface-variant text-sm hidden sm:table-cell">
                                {{ $inv->organization?->name ?? '—' }}
                            </td>
                            <td class="px-4 py-3.5">
                                <span class="font-sans text-xs px-2.5 py-1 rounded-full font-medium
                                    {{ $inv->role === 'secretariat' ? 'bg-[#7c3d1f]/10 text-[#7c3d1f]' :
                                       ($inv->role === 'friend' ? 'bg-blue-100 text-blue-700' : 'bg-surface-container text-on-surface-variant') }}">
                                    {{ ucfirst($inv->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5">
                                @if($inv->isAccepted())
                                    <span class="font-sans inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full bg-green-100 text-green-700 font-medium">
                                        <i class="fa-solid fa-circle-check text-[10px]"></i> Accepted
                                    </span>
                                @elseif($inv->isExpired())
                                    <span class="font-sans inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full bg-surface-container text-on-surface-variant font-medium">
                                        <i class="fa-regular fa-clock text-[10px]"></i> Expired
                                    </span>
                                @else
                                    <span class="font-sans inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 font-medium">
                                        <i class="fa-solid fa-hourglass-half text-[10px]"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td class="font-sans px-4 py-3.5 text-xs text-on-surface-variant hidden md:table-cell">
                                {{ $inv->created_at->format('M j, Y') }}
                            </td>
                            <td class="font-sans px-4 py-3.5 text-xs hidden md:table-cell
                                {{ $inv->isPending() && $inv->expires_at->diffInDays(now()) < 2 ? 'text-red-500 font-medium' : 'text-on-surface-variant' }}">
                                {{ $inv->expires_at->format('M j, Y') }}
                                @if($inv->isPending())
                                    <span class="font-sans block text-[10px] text-on-surface-variant/60">
                                        {{ $inv->expires_at->diffForHumans() }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                @if($inv->isPending())
                                    <button onclick="revokeInvitation('{{ $inv->id }}', this)"
                                            class="font-sans text-xs text-red-400 hover:text-red-600 transition-colors font-medium">
                                        Revoke
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr id="empty-row">
                            <td colspan="7" class="px-5 py-14 text-center">
                                <i class="fa-regular fa-envelope text-3xl text-outline-variant mb-3 block"></i>
                                <p class="font-sans text-on-surface-variant text-sm">No invitations sent yet</p>
                                <p class="font-sans text-on-surface-variant/60 text-xs mt-1">Use the form above to invite your first member</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invitations->hasPages())
            <div class="px-5 py-4 border-t border-surface-container-high">
                {{ $invitations->links() }}
            </div>
        @endif
    </div>

    <script>
    // ── Single Alpine component: invite form + new-org modal ─────────────────
    function invitePage() {
        return {
            // invite form state
            loading: false,
            form: { email: '', role: 'member', organization_id: '', membership_tier_id: '' },
            inviteErrors: {},
            orgs: @json($organizations),
            tiers: @json($tiers),

            // new-org modal state
            orgModal: false,
            orgSaving: false,
            orgForm: { name: '', country: '', type: 'member' },
            orgErrors: {},

            init() {
                this.$watch('orgModal', val => {
                    if (val) this.$nextTick(() => this.$refs.orgNameInput?.focus());
                });
            },

            async send() {
                this.loading = true;
                this.inviteErrors = {};
                try {
                    const res = await fetch('{{ route('admin.invitations.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify(this.form),
                    });
                    const data = await res.json();
                    if (!res.ok) {
                        if (data.errors) this.inviteErrors = data.errors;
                        window.toast('error', data.message ?? 'Failed to send invitation.');
                        return;
                    }
                    if (data.mail_error) {
                        window.toast('warning', 'Invitation saved but email failed to send. Check mail config.', 'Email Error');
                    } else {
                        window.toast('success', data.message, 'Invitation Sent');
                    }
                    prependInvitationRow(data.invitation);
                    bumpStat('stat-total', 1);
                    bumpStat('stat-pending', 1);
                    document.getElementById('total-label').textContent =
                        (parseInt(document.getElementById('stat-total').textContent) + 0) + ' total';
                    this.form = { email: '', role: 'member', organization_id: '', membership_tier_id: '' };
                } catch (err) {
                    window.toast('error', 'Network error. Please try again.');
                } finally {
                    this.loading = false;
                }
            },

            async saveOrg() {
                this.orgSaving = true;
                this.orgErrors = {};
                try {
                    const res = await fetch('{{ route('admin.organizations.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify(this.orgForm),
                    });
                    const data = await res.json();
                    if (!res.ok) {
                        if (data.errors) this.orgErrors = data.errors;
                        window.toast('error', data.message ?? 'Failed to create organization.');
                        return;
                    }
                    this.orgs.push(data);
                    this.form.organization_id = data.id;
                    window.toast('success', `"${data.name}" created and selected.`);
                    this.orgModal = false;
                    this.orgForm = { name: '', country: '', type: 'member' };
                } catch (err) {
                    window.toast('error', 'Network error. Please try again.');
                } finally {
                    this.orgSaving = false;
                }
            },
        };
    }

    // ── Prepend a new row to the table ────────────────────────────────────────
    function prependInvitationRow(inv) {
        const tbody = document.getElementById('invitations-tbody');

        // Remove empty-state row if present
        const empty = document.getElementById('empty-row');
        if (empty) empty.remove();

        const roleClass = inv.role === 'secretariat'
            ? 'bg-[#7c3d1f]/10 text-[#7c3d1f]'
            : (inv.role === 'friend' ? 'bg-blue-100 text-blue-700' : 'bg-surface-container text-on-surface-variant');

        const row = document.createElement('tr');
        row.id = 'inv-row-' + inv.id;
        row.className = 'hover:bg-surface-container/30 transition-colors';
        row.style.opacity = '0';
        row.innerHTML = `
            <td class="px-5 py-3.5">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                        <i class="fa-regular fa-envelope text-[11px] text-primary"></i>
                    </div>
                    <span class="font-sans font-medium text-on-surface text-sm">${escHtml(inv.email)}</span>
                </div>
            </td>
            <td class="font-sans px-4 py-3.5 text-on-surface-variant text-sm hidden sm:table-cell">
                ${inv.organization ? escHtml(inv.organization) : '—'}
            </td>
            <td class="px-4 py-3.5">
                <span class="font-sans text-xs px-2.5 py-1 rounded-full font-medium ${roleClass}">
                    ${capitalise(inv.role)}
                </span>
            </td>
            <td class="px-4 py-3.5">
                <span class="font-sans inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 font-medium">
                    <i class="fa-solid fa-hourglass-half text-[10px]"></i> Pending
                </span>
            </td>
            <td class="font-sans px-4 py-3.5 text-xs text-on-surface-variant hidden md:table-cell">
                ${escHtml(inv.created_at)}
            </td>
            <td class="font-sans px-4 py-3.5 text-xs text-on-surface-variant hidden md:table-cell">
                ${escHtml(inv.expires_at)}
                <span class="block text-[10px] text-on-surface-variant/60">${escHtml(inv.expires_diff)}</span>
            </td>
            <td class="px-4 py-3.5 text-right">
                <button onclick="revokeInvitation('${inv.id}', this)"
                        class="font-sans text-xs text-red-400 hover:text-red-600 transition-colors font-medium">
                    Revoke
                </button>
            </td>
        `;

        tbody.prepend(row);
        requestAnimationFrame(() => {
            row.style.transition = 'opacity 0.3s ease';
            row.style.opacity = '1';
        });
    }

    // ── Revoke via AJAX ───────────────────────────────────────────────────────
    async function revokeInvitation(id, btn) {
        if (!confirm('Revoke this invitation?')) return;

        btn.disabled = true;
        btn.textContent = '…';

        try {
            const res = await fetch(`/admin/invitations/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            });

            const data = await res.json();

            if (res.ok) {
                window.toast('success', data.message ?? 'Invitation revoked.');

                // Fade out and remove the row
                const row = document.getElementById('inv-row-' + id);
                if (row) {
                    row.style.transition = 'opacity 0.3s ease';
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 320);
                }

                // Update counters
                bumpStat('stat-pending', -1);
                bumpStat('stat-total', -1);
            } else {
                window.toast('error', 'Failed to revoke invitation.');
                btn.disabled = false;
                btn.textContent = 'Revoke';
            }
        } catch (err) {
            window.toast('error', 'Network error.');
            btn.disabled = false;
            btn.textContent = 'Revoke';
        }
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    function bumpStat(id, delta) {
        const el = document.getElementById(id);
        if (el) el.textContent = Math.max(0, parseInt(el.textContent || 0) + delta);
    }
    function escHtml(str) {
        return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }
    function capitalise(s) { return s.charAt(0).toUpperCase() + s.slice(1); }

    </script>

</x-app-layout>
