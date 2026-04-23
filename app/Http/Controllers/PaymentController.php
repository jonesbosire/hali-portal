<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\FlutterwaveService;
use App\Services\QuickBooksService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function __construct(
        private FlutterwaveService $flutterwave,
        private QuickBooksService  $quickbooks,
    ) {}

    // ── 1. Initiate checkout ──────────────────────────────────────────────────

    public function initiate(Request $request)
    {
        $user = $request->user();
        $tier = $user->membershipTier;

        if (! $tier) {
            return back()->with('error', 'No membership tier assigned to your account. Please contact the Secretariat.');
        }

        $request->validate([
            'currency' => 'required|in:KES,USD,GHS,NGN,ZAR,UGX,TZS',
        ]);

        $currency = $request->input('currency', 'USD');
        $txRef    = 'HALI-' . strtoupper(Str::random(12));

        $payment = Payment::create([
            'user_id'            => $user->id,
            'membership_tier_id' => $tier->id,
            'gateway'            => 'flutterwave',
            'gateway_reference'  => $txRef,
            'amount'             => $tier->price_usd,
            'currency'           => $currency,
            'status'             => 'pending',
        ]);

        try {
            $result = $this->flutterwave->initiatePayment([
                'tx_ref'          => $txRef,
                'amount'          => (float) $tier->price_usd,
                'currency'        => $currency,
                'redirect_url'    => route('payment.callback'),
                'payment_options' => 'card,mobilemoney,banktransfer',
                'customer'        => [
                    'email'       => $user->email,
                    'name'        => $user->name,
                    'phonenumber' => $user->phone ?? '',
                ],
                'customizations'  => [
                    'title'       => 'HALI Access — Membership Dues',
                    'description' => $tier->name . ' (' . $tier->billing_cycle . ')',
                    'logo'        => asset('images/hali-logo.png'),
                ],
                'meta' => [
                    'user_id'    => $user->id,
                    'tier_id'    => $tier->id,
                    'payment_id' => $payment->id,
                ],
            ]);

            return redirect($result['data']['link']);

        } catch (\Exception $e) {
            $payment->update(['status' => 'failed']);
            Log::error('Flutterwave initiate failed', ['error' => $e->getMessage(), 'user' => $user->id]);
            return back()->with('error', 'Could not connect to the payment gateway. Please try again or contact the Secretariat.');
        }
    }

    // ── 2. Callback (redirect back from Flutterwave) ──────────────────────────

    public function callback(Request $request)
    {
        $status        = $request->query('status');
        $txRef         = $request->query('tx_ref');
        $transactionId = $request->query('transaction_id');

        if ($status !== 'successful' || ! $txRef) {
            return redirect()->route('billing.index')
                ->with('error', 'Payment was not completed. No charge was made.');
        }

        $payment = Payment::where('gateway_reference', $txRef)->first();

        if (! $payment) {
            Log::warning('Flutterwave callback: payment not found', ['tx_ref' => $txRef]);
            return redirect()->route('billing.index')->with('error', 'Payment reference not found.');
        }

        if ($payment->isSuccessful()) {
            return redirect()->route('billing.index')->with('success', 'Payment already processed. Your account is up to date.');
        }

        try {
            $data = $this->flutterwave->verifyTransaction($transactionId);

            if (($data['status'] ?? '') !== 'successful' || (string) $data['tx_ref'] !== $txRef) {
                $payment->update(['status' => 'failed', 'meta' => $data]);
                return redirect()->route('billing.index')->with('error', 'Payment verification failed. Please contact the Secretariat.');
            }

            $this->finalisePayment($payment, $data);

        } catch (\Exception $e) {
            Log::error('Flutterwave callback verify failed', ['error' => $e->getMessage(), 'tx_ref' => $txRef]);
            return redirect()->route('billing.index')->with('error', 'Could not verify payment. Please contact the Secretariat.');
        }

        return redirect()->route('billing.index')->with('success', 'Payment successful! Your membership dues are now paid.');
    }

    // ── 3. Webhook (background confirmation from Flutterwave) ─────────────────

    public function webhook(Request $request): Response
    {
        $signature = $request->header('verif-hash', '');

        if (! $this->flutterwave->verifyWebhookSignature($signature)) {
            Log::warning('Flutterwave webhook: invalid signature');
            return response('Forbidden', 403);
        }

        $payload = $request->json()->all();
        $event   = $payload['event'] ?? '';

        Log::info("Flutterwave webhook: {$event}", ['tx_ref' => $payload['data']['tx_ref'] ?? null]);

        if (in_array($event, ['charge.completed', 'charge.success'])) {
            $data   = $payload['data'] ?? [];
            $txRef  = $data['tx_ref'] ?? null;

            if (! $txRef) {
                return response('OK', 200);
            }

            $payment = Payment::where('gateway_reference', $txRef)->first();

            if ($payment && ! $payment->isSuccessful() && ($data['status'] ?? '') === 'successful') {
                try {
                    $verified = $this->flutterwave->verifyTransaction($data['id']);
                    if (($verified['status'] ?? '') === 'successful') {
                        $this->finalisePayment($payment, $verified);
                    }
                } catch (\Exception $e) {
                    Log::error('Flutterwave webhook verify failed', ['error' => $e->getMessage(), 'tx_ref' => $txRef]);
                }
            }
        }

        return response('OK', 200);
    }

    // ── Shared: mark payment successful + extend user dues ────────────────────

    private function finalisePayment(Payment $payment, array $gatewayData): void
    {
        $payment->update([
            'status'                => 'successful',
            'gateway_transaction_id' => (string) ($gatewayData['id'] ?? ''),
            'payment_method'        => $gatewayData['payment_type'] ?? null,
            'currency'              => $gatewayData['currency'] ?? $payment->currency,
            'paid_at'               => now(),
            'meta'                  => $gatewayData,
        ]);

        // Extend dues by one year from today (or from current due date if not yet expired)
        $user = $payment->user;
        if ($user) {
            $existingDue  = $user->dues_due_date
                ? \Carbon\Carbon::parse($user->dues_due_date)
                : null;
            $base         = ($existingDue && $existingDue->isFuture()) ? $existingDue : now();
            $newDueDate   = $base->copy()->addYear()->toDateString();

            $user->update(['dues_due_date' => $newDueDate]);

            // Re-activate if suspended due to overdue dues
            if ($user->status === 'suspended') {
                $user->update(['status' => 'active']);
            }
        }

        activity()
            ->causedBy($user)
            ->performedOn($payment)
            ->withProperties(['amount' => $payment->amount, 'currency' => $payment->currency])
            ->log('payment_successful');

        // Sync to QuickBooks Online — silently skip if QB not connected
        if ($user && $this->quickbooks->isConnected()) {
            try {
                $tier = $payment->tier;
                $qbCustomerId = $this->quickbooks->findOrCreateCustomer($user->name, $user->email);
                $qbResult     = $this->quickbooks->createPaidInvoice(
                    customerId:  $qbCustomerId,
                    description: ($tier?->name ?? 'Membership Dues') . ' — ' . now()->format('Y'),
                    amount:      (float) $payment->amount,
                    currency:    $payment->currency,
                );
                $payment->update([
                    'quickbooks_invoice_id' => $qbResult['invoice_id'],
                    'meta'                  => array_merge((array) $payment->meta, ['qb_invoice_id' => $qbResult['invoice_id']]),
                ]);
            } catch (\Exception $e) {
                Log::error('QuickBooks invoice sync failed', ['payment' => $payment->id, 'error' => $e->getMessage()]);
            }
        }
    }
}
