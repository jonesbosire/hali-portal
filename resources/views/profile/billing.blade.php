<x-app-layout title="Billing">
    <div class="max-w-2xl">

        <div class="mb-6">
            <h1 class="font-sans text-xl font-bold text-on-surface">Billing & Membership Dues</h1>
            <p class="font-sans text-sm text-on-surface-variant mt-0.5">Pay your annual membership dues and view your payment history.</p>
        </div>

        @if(session('error'))
            <div class="font-sans mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3">
                <i class="fa-solid fa-circle-exclamation text-red-500 flex-shrink-0"></i>
                {{ session('error') }}
            </div>
        @endif
        @if(session('success'))
            <div class="font-sans mb-5 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-4 py-3">
                <i class="fa-solid fa-circle-check text-green-600 flex-shrink-0"></i>
                {{ session('success') }}
            </div>
        @endif

        @php
            $authUser = auth()->user();
            $userTier = $authUser->membershipTier;
        @endphp

        {{-- Membership dues card --}}
        @if($userTier)
            @php
                $duesOverdue = $authUser->duesOverdue();
                $duesInGrace = $authUser->duesInGracePeriod();
                $duesSoon    = $authUser->duesSoon(14);
                $cardBorder  = $duesOverdue ? 'border-red-200' : ($duesInGrace ? 'border-amber-200' : 'border-surface-container-high');
            @endphp
            <div class="bg-white rounded-2xl {{ $cardBorder }} border shadow-card p-6 mb-5" x-data="paymentForm()">

                <div class="flex items-start justify-between gap-3 mb-5">
                    <div>
                        <h2 class="font-sans text-sm font-semibold text-on-surface">Membership Dues</h2>
                        <p class="font-sans text-2xl font-bold text-on-surface mt-1">{{ $userTier->name }}</p>
                        <p class="font-sans text-on-surface-variant text-sm">{{ $userTier->getFormattedPriceAttribute() }}</p>
                    </div>
                    @if($duesOverdue)
                        <span class="font-sans text-xs font-bold text-red-600 bg-red-100 px-3 py-1 rounded-full">Overdue</span>
                    @elseif($duesInGrace)
                        <span class="font-sans text-xs font-bold text-amber-700 bg-amber-100 px-3 py-1 rounded-full">Grace Period</span>
                    @elseif($duesSoon)
                        <span class="font-sans text-xs font-bold text-amber-600 bg-amber-50 border border-amber-200 px-3 py-1 rounded-full">Due Soon</span>
                    @else
                        <span class="font-sans text-xs font-bold text-green-700 bg-green-100 px-3 py-1 rounded-full">Paid</span>
                    @endif
                </div>

                @if($authUser->dues_due_date)
                    <div class="bg-surface-container-lowest rounded-xl px-4 py-3 mb-5 text-sm">
                        <p class="font-sans text-on-surface-variant">
                            Dues {{ $duesOverdue ? 'were due on' : 'due on' }}:
                            <span class="font-semibold {{ $duesOverdue ? 'text-red-600' : ($duesInGrace ? 'text-amber-700' : 'text-on-surface') }}">
                                {{ $authUser->dues_due_date->format('F j, Y') }}
                            </span>
                        </p>
                        @if($duesInGrace)
                            <p class="font-sans text-amber-700 text-xs mt-1">
                                <i class="fa-solid fa-triangle-exclamation text-[10px] mr-1"></i>
                                Account suspends {{ $authUser->dues_due_date->addDays(7)->diffForHumans() }}
                            </p>
                        @endif
                    </div>
                @endif

                @if($userTier->features && count($userTier->features))
                    <ul class="space-y-1.5 mb-5">
                        @foreach($userTier->features as $feature)
                            <li class="font-sans flex items-start gap-2 text-sm text-on-surface-variant">
                                <i class="fa-solid fa-check text-[11px] text-primary mt-0.5 flex-shrink-0"></i>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                @endif

                {{-- Pay now --}}
                <form @submit.prevent="pay">
                    <div class="flex flex-wrap items-end gap-3">
                        <div>
                            <label class="font-sans block text-xs font-semibold text-on-surface-variant mb-1.5 uppercase tracking-wide">Currency</label>
                            <select x-model="currency" name="currency"
                                    class="font-sans text-sm border border-outline-variant rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-primary/20 bg-white">
                                <option value="USD">USD — US Dollar</option>
                                <option value="KES">KES — Kenyan Shilling</option>
                                <option value="GHS">GHS — Ghanaian Cedi</option>
                                <option value="NGN">NGN — Nigerian Naira</option>
                                <option value="ZAR">ZAR — South African Rand</option>
                                <option value="UGX">UGX — Ugandan Shilling</option>
                                <option value="TZS">TZS — Tanzanian Shilling</option>
                            </select>
                        </div>
                        <button type="submit" :disabled="loading"
                                class="font-sans inline-flex items-center gap-2 bg-[#7c3d1f] hover:bg-[#6b3218] disabled:opacity-60 text-white text-sm font-bold px-6 py-2.5 rounded-xl transition-colors">
                            <i class="fa-solid text-xs" :class="loading ? 'fa-spinner fa-spin' : 'fa-credit-card'"></i>
                            <span x-text="loading ? 'Redirecting…' : 'Pay Membership Dues'"></span>
                        </button>
                    </div>
                    <p class="font-sans text-xs text-on-surface-variant mt-3 flex items-center gap-1.5">
                        <i class="fa-solid fa-lock text-[10px]"></i>
                        Secure checkout via Flutterwave. Card, M-Pesa, and bank transfer accepted.
                    </p>
                </form>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-surface-container-high shadow-card p-6 mb-5 text-center">
                <i class="fa-solid fa-id-card text-3xl text-outline-variant mb-3 block"></i>
                <p class="font-sans font-semibold text-on-surface mb-1">No membership tier assigned</p>
                <p class="font-sans text-sm text-on-surface-variant">The Secretariat will assign your membership tier. Contact them if you believe this is an error.</p>
            </div>
        @endif

        {{-- Payment history --}}
        @php $payments = $authUser->payments()->with('tier')->orderByDesc('created_at')->take(20)->get(); @endphp
        <div class="bg-white rounded-2xl border border-surface-container-high shadow-card overflow-hidden">
            <div class="px-5 py-3.5 border-b border-surface-container-high">
                <h2 class="font-sans text-sm font-semibold text-on-surface">Payment History</h2>
            </div>
            @if($payments->isEmpty())
                <div class="font-sans p-10 text-center text-sm text-on-surface-variant">No payments yet</div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm font-sans">
                        <thead class="bg-surface-container/50 border-b border-surface-container-high">
                            <tr>
                                <th class="text-left text-xs font-semibold text-on-surface-variant px-5 py-3 uppercase tracking-wide">Reference</th>
                                <th class="text-left text-xs font-semibold text-on-surface-variant px-4 py-3 uppercase tracking-wide">Tier</th>
                                <th class="text-left text-xs font-semibold text-on-surface-variant px-4 py-3 uppercase tracking-wide">Amount</th>
                                <th class="text-left text-xs font-semibold text-on-surface-variant px-4 py-3 uppercase tracking-wide">Status</th>
                                <th class="text-left text-xs font-semibold text-on-surface-variant px-4 py-3 uppercase tracking-wide hidden md:table-cell">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-container-high">
                            @foreach($payments as $payment)
                                <tr class="hover:bg-surface-container/30 transition-colors">
                                    <td class="px-5 py-3 text-xs font-mono text-on-surface-variant">{{ Str::upper(substr($payment->gateway_reference, 0, 16)) }}</td>
                                    <td class="px-4 py-3 text-sm text-on-surface">{{ $payment->tier?->name ?? '—' }}</td>
                                    <td class="px-4 py-3 font-semibold text-on-surface">${{ number_format($payment->amount, 2) }} <span class="text-xs font-normal text-on-surface-variant">{{ $payment->currency }}</span></td>
                                    <td class="px-4 py-3">
                                        <span class="text-xs px-2.5 py-1 rounded-full font-medium
                                            {{ $payment->status === 'successful' ? 'bg-green-100 text-green-700'
                                             : ($payment->status === 'pending'    ? 'bg-amber-100 text-amber-700'
                                             : 'bg-red-100 text-red-600') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-on-surface-variant hidden md:table-cell">
                                        {{ $payment->paid_at?->format('M j, Y') ?? $payment->created_at->format('M j, Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script>
    function paymentForm() {
        return {
            loading: false,
            currency: 'USD',

            async pay() {
                this.loading = true;
                try {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('payment.initiate') }}';

                    const csrf = document.createElement('input');
                    csrf.type  = 'hidden';
                    csrf.name  = '_token';
                    csrf.value = document.querySelector('meta[name="csrf-token"]').content;
                    form.appendChild(csrf);

                    const cur = document.createElement('input');
                    cur.type  = 'hidden';
                    cur.name  = 'currency';
                    cur.value = this.currency;
                    form.appendChild(cur);

                    document.body.appendChild(form);
                    form.submit();
                } catch {
                    this.loading = false;
                }
            },
        };
    }
    </script>

</x-app-layout>
