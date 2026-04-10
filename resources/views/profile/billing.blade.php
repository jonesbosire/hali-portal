<x-app-layout title="Billing">
    <div class="max-w-2xl">
        <h1 class="text-xl font-bold text-hali-text-primary mb-6">Billing & Subscription</h1>

        {{-- Current plan --}}
        <div class="bg-white rounded-2xl border border-hali-border shadow-card p-6 mb-5">
            <h2 class="text-sm font-semibold text-hali-text-primary mb-4">Current Plan</h2>
            @if($subscription && $subscription->isActive())
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-lg font-bold text-primary">{{ $subscription->plan->name }}</p>
                        <p class="text-sm text-hali-text-secondary">
                            ${{ number_format($subscription->plan->price_usd, 2) }} / {{ $subscription->plan->billing_cycle }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            Renews {{ $subscription->current_period_end?->format('F j, Y') }}
                        </p>
                    </div>
                    <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1.5 rounded-full capitalize">
                        {{ $subscription->status }}
                    </span>
                </div>
                @if($subscription->plan->features)
                    <ul class="mt-4 space-y-1">
                        @foreach($subscription->plan->features as $feature)
                            <li class="flex items-center gap-2 text-sm text-hali-text-secondary">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            @elseif($subscription && $subscription->isPastDue())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <p class="text-red-700 font-semibold">Payment Overdue</p>
                    <p class="text-red-600 text-sm mt-1">Please update your payment to continue accessing the portal.</p>
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-hali-text-secondary text-sm mb-4">No active subscription. Choose a plan below to continue.</p>
                </div>
            @endif
        </div>

        {{-- Available plans --}}
        <div class="bg-white rounded-2xl border border-hali-border shadow-card p-6 mb-5">
            <h2 class="text-sm font-semibold text-hali-text-primary mb-4">Available Plans</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($plans as $plan)
                    <div class="border rounded-xl p-4 {{ $subscription?->plan_id === $plan->id ? 'border-primary bg-primary-50' : 'border-hali-border' }}">
                        <p class="font-bold text-hali-text-primary">{{ $plan->name }}</p>
                        <p class="text-xl font-bold text-primary mt-1">${{ number_format($plan->price_usd, 0) }}<span class="text-sm font-normal text-hali-text-secondary">/yr</span></p>
                        @if($plan->description)
                            <p class="text-xs text-hali-text-secondary mt-2">{{ $plan->description }}</p>
                        @endif
                        @if($subscription?->plan_id !== $plan->id)
                            <button type="button" onclick="alert('Stripe integration required — contact the HALI Secretariat to upgrade.')"
                                    class="mt-3 w-full text-xs bg-primary text-white py-2 rounded-lg hover:bg-primary-dark transition-colors">
                                Select Plan
                            </button>
                        @else
                            <p class="mt-3 text-xs text-center text-primary font-semibold">✓ Current Plan</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Invoice history --}}
        <div class="bg-white rounded-2xl border border-hali-border shadow-card overflow-hidden">
            <div class="p-5 border-b border-hali-border">
                <h2 class="text-sm font-semibold text-hali-text-primary">Invoice History</h2>
            </div>
            @if($invoices->isEmpty())
                <div class="p-8 text-center text-sm text-hali-text-secondary">No invoices yet</div>
            @else
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-hali-border">
                        <tr>
                            <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3">Invoice</th>
                            <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3">Amount</th>
                            <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3">Status</th>
                            <th class="text-left text-xs font-semibold text-hali-text-secondary px-5 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-hali-border">
                        @foreach($invoices as $invoice)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-3 text-xs text-hali-text-secondary font-mono">{{ substr($invoice->id, 0, 8) }}...</td>
                                <td class="px-5 py-3 font-medium">${{ number_format($invoice->amount_usd, 2) }}</td>
                                <td class="px-5 py-3">
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-xs text-hali-text-secondary">{{ $invoice->created_at->format('M j, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>
