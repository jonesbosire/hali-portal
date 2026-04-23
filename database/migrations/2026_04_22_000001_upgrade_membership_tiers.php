<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Extend membership_plans into a full tier system
        Schema::table('membership_plans', function (Blueprint $table) {
            $table->enum('tier_type', ['member', 'friend'])->default('member')->after('slug');
        });

        // Add tier assignment and dues tracking to users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignUuid('membership_tier_id')->nullable()->after('status')
                  ->constrained('membership_plans')->nullOnDelete();
            $table->date('dues_due_date')->nullable()->after('membership_tier_id');
        });

        // Add tier to invitations so Secretariat assigns tier at invite time
        Schema::table('invitations', function (Blueprint $table) {
            $table->foreignUuid('membership_tier_id')->nullable()->after('role')
                  ->constrained('membership_plans')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropForeign(['membership_tier_id']);
            $table->dropColumn('membership_tier_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['membership_tier_id']);
            $table->dropColumn(['membership_tier_id', 'dues_due_date']);
        });

        Schema::table('membership_plans', function (Blueprint $table) {
            $table->dropColumn('tier_type');
        });
    }
};
