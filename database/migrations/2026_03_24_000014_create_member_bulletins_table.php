<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_bulletins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->longText('content')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->integer('recipient_count')->default(0);
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['draft', 'sent'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_bulletins');
    }
};
