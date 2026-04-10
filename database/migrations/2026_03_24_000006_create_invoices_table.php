<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('subscription_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount_usd', 10, 2);
            $table->enum('status', ['paid', 'unpaid', 'void'])->default('unpaid');
            $table->string('stripe_invoice_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->string('pdf_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
