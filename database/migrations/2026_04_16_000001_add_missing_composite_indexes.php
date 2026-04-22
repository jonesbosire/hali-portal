<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // event_registrations: composite (event_id, status) used in attendees() scope
        // which is called by isFull() and spotsLeft() on every event detail page.
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->index(['event_id', 'status'], 'event_registrations_event_status_idx');
        });

        // notifications: (notifiable_type, notifiable_id, read_at) for the unread count
        // query that runs on every authenticated page load (sidebar).
        Schema::table('notifications', function (Blueprint $table) {
            $table->index(
                ['notifiable_type', 'notifiable_id', 'read_at'],
                'notifications_notifiable_read_idx'
            );
        });

        // posts: author_id used in user profile pages and admin member show
        Schema::table('posts', function (Blueprint $table) {
            $table->index('author_id', 'posts_author_id_idx');
        });

        // opportunities: deadline_at used in active() scope filter
        Schema::table('opportunities', function (Blueprint $table) {
            $table->index('deadline_at', 'opportunities_deadline_idx');
        });
    }

    public function down(): void
    {
        Schema::table('event_registrations', function (Blueprint $table) {
            $table->dropIndex('event_registrations_event_status_idx');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_notifiable_read_idx');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_author_id_idx');
        });

        Schema::table('opportunities', function (Blueprint $table) {
            $table->dropIndex('opportunities_deadline_idx');
        });
    }
};
