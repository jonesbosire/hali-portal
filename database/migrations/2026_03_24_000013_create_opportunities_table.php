<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opportunities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->foreignUuid('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('posted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type', ['job', 'fellowship', 'scholarship', 'internship', 'volunteer'])->default('job');
            $table->longText('description')->nullable();
            $table->text('requirements')->nullable();
            $table->string('location')->nullable();
            $table->string('salary_range')->nullable();
            $table->string('application_url')->nullable();
            $table->timestamp('deadline_at')->nullable();
            $table->enum('status', ['active', 'expired', 'filled'])->default('active');
            $table->boolean('is_members_only')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
