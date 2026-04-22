<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_programs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('event_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('speaker')->nullable();     // name of speaker / facilitator
            $table->string('speaker_title')->nullable(); // e.g. "Director, XYZ Foundation"
            $table->time('start_time')->nullable();    // e.g. 09:00
            $table->time('end_time')->nullable();      // e.g. 10:30
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_programs');
    }
};
