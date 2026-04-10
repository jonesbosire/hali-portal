<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['document', 'link', 'video', 'template'])->default('document');
            $table->string('file_path')->nullable();
            $table->string('external_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->boolean('is_members_only')->default(true);
            $table->unsignedBigInteger('download_count')->default(0);
            $table->foreignUuid('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
