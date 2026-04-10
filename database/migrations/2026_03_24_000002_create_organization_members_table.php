<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organization_members', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('organization_id')->constrained()->cascadeOnDelete();
            $table->enum('role', ['primary_contact', 'staff', 'admin'])->default('staff');
            $table->boolean('is_primary')->default(false);
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'organization_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organization_members');
    }
};
