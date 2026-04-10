<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['member', 'friend'])->default('member');
            $table->string('country')->nullable();
            $table->string('region')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('website_url')->nullable();
            $table->text('description')->nullable();
            $table->smallInteger('founding_year')->unsigned()->nullable();
            $table->integer('students_supported')->default(0);
            $table->decimal('scholarship_total', 12, 2)->default(0);
            $table->enum('status', ['active', 'pending', 'inactive'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
