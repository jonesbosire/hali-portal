<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Organization;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class BillingController extends Controller
{
    // ── Member-facing billing page ────────────────────────────────────────────

    public function index(Request $request)
    {
        $user = $request->user();
        return view('profile.billing', compact('user'));
    }

    // ── Stripe Customer Portal ────────────────────────────────────────────────
    //
    // Redirects the user to Stripe's hosted billing portal where they can update
    // their card, download invoices, and manage their subscription.
    // Requires the Stripe Billing Portal to be enabled in the Stripe dashboard.

    public function portal(Request $request)
    {
        $user         = $request->user();
        $organization = $user->primaryOrganization();
        $subscription = $organization?->subscription;

        if (!$subscription?->stripe_customer_id) {
            return back()->with('error', 'No active subscription found. Contact the HALI Secretariat for assistance.');
        }

        $stripeKey = config('cashier.secret');
        if (!$stripeKey) {
            return back()->with('error', 'Billing is not configured. Contact the HALI Secretariat.');
        }

        try {
            \Stripe\Stripe::setApiKey($stripeKey);

            $session = \Stripe\BillingPortal\Session::create([
                'customer'   => $subscription->stripe_customer_id,
                'return_url' => route('billing.index'),
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            Log::error('Stripe billing portal error.', ['error' => $e->getMessage()]);
            return back()->with('error', 'Could not open the billing portal. Please try again or contact the Secretariat.');
        }
    }

    // ── Stripe webhook ────────────────────────────────────────────────────────
    //
    // This endpoint is EXEMPT from CSRF (registered in web.php without auth middleware).
    // Security is enforced entirely by Stripe's HMAC-SHA256 signature on every request.
    //
    // Required .env key: STRIPE_WEBHOOK_SECRET (from Stripe dashboard → Webhooks → signing secret)

    public function webhook(Request $request): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature', '');
        $secret    = config('cashier.webhook.secret');

        if (empty($secret)) {
            Log::error('Stripe webhook secret not configured. Set STRIPE_WEBHOOK_SECRET in .env');
            return response('Webhook secret not configured.', 500);
        }

        // ── 1. Verify signature — reject anything that doesn't match ─────────
        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);
        } catch (SignatureVerificationException $e) {
            Log::warning('Stripe webhook signature mismatch.', ['error' => $e->getMessage()]);
            return response('Invalid signature.', 400);
        } catch (\UnexpectedValueException $e) {
            Log::warning('Stripe webhook payload invalid.', ['error' => $e->getMessage()]);
            return response('Invalid payload.', 400);
        }

        // ── 2. Dispatch to the right handler ─────────────────────────────────
        Log::info("Stripe webhook received: {$event->type}", ['id' => $event->id]);

        match ($event->type) {
            'customer.subscription.created',
            'customer.subscription.updated'  => $this->handleSubscriptionUpdated($event->data->object),
            'customer.subscription.deleted'  => $this->handleSubscriptionDeleted($event->data->object),
            'invoice.payment_succeeded'      => $this->handleInvoicePaid($event->data->object),
            'invoice.payment_failed'         => $this->handleInvoicePaymentFailed($event->data->object),
            default                          => null, // ignore unhandled events
        };

        return response('OK', 200);
    }

    // ── Webhook handlers ──────────────────────────────────────────────────────

    private function handleSubscriptionUpdated(object $stripeSubscription): void
    {
        $sub = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();

        if (!$sub) {
            // First time we've seen this subscription — find the org by Stripe customer ID
            $org = Organization::whereHas('subscription',
                fn ($s) => $s->where('stripe_customer_id', $stripeSubscription->customer)
            )->first();

            if (!$org) {
                Log::warning("Stripe subscription {$stripeSubscription->id}: no matching organization found.");
                return;
            }

            $sub = new Subscription();
            $sub->organization_id = $org->id;
        }

        $sub->stripe_subscription_id = $stripeSubscription->id;
        $sub->stripe_customer_id     = $stripeSubscription->customer;
        $sub->status                 = $stripeSubscription->status;
        $sub->current_period_start   = Carbon::createFromTimestamp($stripeSubscription->current_period_start);
        $sub->current_period_end     = Carbon::createFromTimestamp($stripeSubscription->current_period_end);
        $sub->canceled_at            = $stripeSubscription->canceled_at
            ? Carbon::createFromTimestamp($stripeSubscription->canceled_at)
            : null;
        $sub->save();
    }

    private function handleSubscriptionDeleted(object $stripeSubscription): void
    {
        $sub = Subscription::where('stripe_subscription_id', $stripeSubscription->id)->first();
        if ($sub) {
            $sub->update([
                'status'      => 'canceled',
                'canceled_at' => now(),
            ]);
        }
    }

    private function handleInvoicePaid(object $stripeInvoice): void
    {
        // Avoid duplicate records via idempotency on stripe_invoice_id
        if (Invoice::where('stripe_invoice_id', $stripeInvoice->id)->exists()) {
            return;
        }

        $sub = Subscription::where('stripe_subscription_id', $stripeInvoice->subscription)->first();
        if (!$sub) return;

        Invoice::create([
            'organization_id'  => $sub->organization_id,
            'subscription_id'  => $sub->id,
            'stripe_invoice_id' => $stripeInvoice->id,
            'amount_usd'       => $stripeInvoice->amount_paid / 100,
            'status'           => 'paid',
            'paid_at'          => Carbon::createFromTimestamp($stripeInvoice->status_transitions->paid_at ?? now()->timestamp),
            'due_date'         => $stripeInvoice->due_date
                ? Carbon::createFromTimestamp($stripeInvoice->due_date)
                : null,
            'pdf_path'         => $stripeInvoice->invoice_pdf ?? null,
        ]);
    }

    private function handleInvoicePaymentFailed(object $stripeInvoice): void
    {
        $sub = Subscription::where('stripe_subscription_id', $stripeInvoice->subscription)->first();
        if (!$sub) return;

        // Mark subscription past_due
        $sub->update(['status' => 'past_due']);

        // Create an unpaid invoice record
        if (!Invoice::where('stripe_invoice_id', $stripeInvoice->id)->exists()) {
            Invoice::create([
                'organization_id'  => $sub->organization_id,
                'subscription_id'  => $sub->id,
                'stripe_invoice_id' => $stripeInvoice->id,
                'amount_usd'       => $stripeInvoice->amount_due / 100,
                'status'           => 'open',
                'due_date'         => $stripeInvoice->due_date
                    ? Carbon::createFromTimestamp($stripeInvoice->due_date)
                    : null,
            ]);
        }

        Log::warning("Stripe invoice payment failed for organization {$sub->organization_id}.", [
            'invoice_id' => $stripeInvoice->id,
            'amount'     => $stripeInvoice->amount_due / 100,
        ]);
    }
}
