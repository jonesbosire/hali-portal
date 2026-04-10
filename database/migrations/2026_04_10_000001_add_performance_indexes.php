<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // users: role and status+role are queried together on admin dashboard and bulletin send
        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'users_role_idx');
            $table->index(['status', 'role'], 'users_status_role_idx');
        });

        // posts: status is used in every published() scope
        Schema::table('posts', function (Blueprint $table) {
            $table->index('status', 'posts_status_idx');
            $table->index(['status', 'published_at'], 'posts_status_published_at_idx');
        });

        // events: status+start_datetime used in published()->upcoming() compound scope
        Schema::table('events', function (Blueprint $table) {
            $table->index('status', 'events_status_idx');
            $table->index(['status', 'start_datetime'], 'events_status_start_datetime_idx');
        });

        // opportunities: status used in active() scope
        Schema::table('opportunities', function (Blueprint $table) {
            $table->index('status', 'opportunities_status_idx');
        });

        // organizations: status and country used in directory filtering
        Schema::table('organizations', function (Blueprint $table) {
            $table->index('status', 'organizations_status_idx');
            $table->index('country', 'organizations_country_idx');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_idx');
            $table->dropIndex('users_status_role_idx');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_status_idx');
            $table->dropIndex('posts_status_published_at_idx');
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('events_status_idx');
            $table->dropIndex('events_status_start_datetime_idx');
        });

        Schema::table('opportunities', function (Blueprint $table) {
            $table->dropIndex('opportunities_status_idx');
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->dropIndex('organizations_status_idx');
            $table->dropIndex('organizations_country_idx');
        });
    }
};
