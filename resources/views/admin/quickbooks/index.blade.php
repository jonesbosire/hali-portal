<x-app-layout title="QuickBooks — Admin">

    <div class="mb-6">
        <h1 class="font-sans text-xl font-bold text-on-surface">QuickBooks Integration</h1>
        <p class="font-sans text-sm text-on-surface-variant mt-0.5">Connect QuickBooks Online to automatically generate invoices when members pay their dues.</p>
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

    <div class="max-w-lg">
        <div class="bg-white rounded-2xl border border-surface-container-high shadow-card p-6">

            <div class="flex items-center gap-4 mb-6">
                <div class="w-12 h-12 rounded-xl bg-[#2ca01c]/10 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-receipt text-[#2ca01c] text-xl"></i>
                </div>
                <div>
                    <p class="font-sans font-bold text-on-surface">QuickBooks Online</p>
                    @if($connected)
                        <span class="font-sans inline-flex items-center gap-1.5 text-xs font-semibold text-green-700 bg-green-100 px-2.5 py-0.5 rounded-full mt-0.5">
                            <i class="fa-solid fa-circle text-[8px]"></i> Connected
                        </span>
                    @else
                        <span class="font-sans inline-flex items-center gap-1.5 text-xs font-semibold text-on-surface-variant bg-surface-container px-2.5 py-0.5 rounded-full mt-0.5">
                            <i class="fa-regular fa-circle text-[8px]"></i> Not connected
                        </span>
                    @endif
                </div>
            </div>

            @if($connected)
                <p class="font-sans text-sm text-on-surface-variant mb-5">
                    QuickBooks is connected. When a member completes a payment, a customer record and paid invoice will be created in QuickBooks automatically.
                </p>
                <div class="flex items-center gap-3">
                    <form method="POST" action="{{ route('admin.quickbooks.authorize') }}">
                        @csrf
                        <button type="submit"
                                class="font-sans text-sm px-4 py-2 rounded-xl border border-outline-variant hover:bg-surface-container-low text-on-surface transition-colors">
                            <i class="fa-solid fa-rotate mr-1.5 text-xs"></i> Re-authorize
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.quickbooks.disconnect') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Disconnect QuickBooks? New payments will no longer generate invoices.')"
                                class="font-sans text-sm px-4 py-2 rounded-xl border border-red-200 text-red-600 hover:bg-red-50 transition-colors">
                            <i class="fa-solid fa-unlink mr-1.5 text-xs"></i> Disconnect
                        </button>
                    </form>
                </div>
            @else
                <p class="font-sans text-sm text-on-surface-variant mb-2">
                    Connect QuickBooks Online so the portal can automatically:
                </p>
                <ul class="font-sans text-sm text-on-surface-variant space-y-1.5 mb-5 ml-1">
                    <li class="flex items-start gap-2"><i class="fa-solid fa-check text-[11px] text-primary mt-0.5"></i> Create a customer record for each paying member</li>
                    <li class="flex items-start gap-2"><i class="fa-solid fa-check text-[11px] text-primary mt-0.5"></i> Generate a paid invoice for each membership dues payment</li>
                    <li class="flex items-start gap-2"><i class="fa-solid fa-check text-[11px] text-primary mt-0.5"></i> Keep your QuickBooks accounting in sync without manual entry</li>
                </ul>
                <p class="font-sans text-xs text-on-surface-variant mb-4 bg-amber-50 border border-amber-200 rounded-xl px-3 py-2.5">
                    <i class="fa-solid fa-triangle-exclamation text-amber-500 mr-1.5"></i>
                    You will be redirected to Intuit to authorise access. Make sure you are signed into the correct QuickBooks Online account first.
                </p>
                <form method="POST" action="{{ route('admin.quickbooks.authorize') }}">
                    @csrf
                    <button type="submit"
                            class="font-sans inline-flex items-center gap-2 bg-[#2ca01c] hover:bg-[#258a17] text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors">
                        <i class="fa-solid fa-plug text-xs"></i>
                        Connect QuickBooks Online
                    </button>
                </form>
            @endif
        </div>
    </div>

</x-app-layout>
