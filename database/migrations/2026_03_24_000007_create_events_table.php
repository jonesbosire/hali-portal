<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->enum('type', ['webinar', 'conference', 'workshop', 'indaba', 'other'])->default('other');
            $table->timestamp('start_datetime');
            $table->timestamp('end_datetime')->nullable();
            $table->string('timezone')->default('Africa/Nairobi');
            $table->enum('location_type', ['virtual', 'in_person', 'hybrid'])->default('virtual');
            $table->string('venue_name')->nullable();
            $table->text('venue_address')->nullable();
            $table->string('virtual_link')->nullable();
            $table->string('cover_image')->nullable();
            $table->integer('max_attendees')->nullable();
            $table->boolean('is_members_only')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('registration_opens_at')->nullable();
            $table->timestamp('registration_closes_at')->nullable();
            $table->enum('status', ['draft', 'published', 'canceled'])->default('draft');
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
