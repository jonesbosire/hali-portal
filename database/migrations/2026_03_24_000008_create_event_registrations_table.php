<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['registered', 'waitlisted', 'canceled', 'attended'])->default('registered');
            $table->text('registration_notes')->nullable();
            $table->string('dietary_requirements')->nullable();
            $table->timestamp('registered_at')->useCurrent();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('attended_at')->nullable();
            $table->timestamps();

            $table->unique(['event_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
};
