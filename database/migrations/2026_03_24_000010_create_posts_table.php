<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->enum('type', ['update', 'story', 'blog', 'bulletin', 'resource'])->default('update');
            $table->foreignUuid('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('organization_id')->nullable()->constrained()->nullOnDelete();
            $table->string('cover_image')->nullable();
            $table->boolean('is_members_only')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('post_category_pivot', function (Blueprint $table) {
            $table->foreignUuid('post_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('category_id')->constrained('post_categories')->cascadeOnDelete();
            $table->primary(['post_id', 'category_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_category_pivot');
        Schema::dropIfExists('posts');
    }
};
