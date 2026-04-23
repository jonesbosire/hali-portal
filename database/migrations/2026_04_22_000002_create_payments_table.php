<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('membership_tier_id')->nullable()->constrained('membership_plans')->nullOnDelete();
            $table->enum('gateway', ['flutterwave', 'stripe'])->default('flutterwave');
            $table->string('gateway_reference')->nullable(); // Flutterwave tx_ref / Stripe payment_intent
            $table->string('gateway_transaction_id')->nullable(); // Flutterwave flw_ref
            $table->decimal('amount', 10, 2);
            $table->string('currency', 10)->default('USD');
            $table->enum('status', ['pending', 'successful', 'failed', 'cancelled'])->default('pending');
            $table->string('payment_method')->nullable(); // card, mpesa, bank_transfer
            $table->string('quickbooks_invoice_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('meta')->nullable(); // raw gateway response
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('gateway_reference');
        });

        // Extend invoices to support user-level dues (not just org subscriptions)
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignUuid('user_id')->nullable()->after('organization_id')
                  ->constrained()->nullOnDelete();
            $table->foreignUuid('payment_id')->nullable()->after('subscription_id')
                  ->constrained('payments')->nullOnDelete();
            $table->string('quickbooks_invoice_id')->nullable()->after('stripe_invoice_id');
            $table->string('quickbooks_customer_id')->nullable()->after('quickbooks_invoice_id');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['payment_id']);
            $table->dropColumn(['user_id', 'payment_id', 'quickbooks_invoice_id', 'quickbooks_customer_id']);
        });

        Schema::dropIfExists('payments');
    }
};
