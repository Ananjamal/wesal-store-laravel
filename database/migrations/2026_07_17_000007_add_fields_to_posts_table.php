<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('article_category_id')
                ->nullable()
                ->after('user_id')
                ->constrained('article_categories')
                ->nullOnDelete();

            $table->string('meta_title')->nullable()->after('featured_image');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('keywords')->nullable()->after('meta_description');
            $table->boolean('is_featured')->default(false)->after('keywords');

            $table->index(['is_featured', 'status']);
            $table->index('article_category_id');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['article_category_id']);
            $table->dropIndex(['is_featured', 'status']);
            $table->dropIndex(['article_category_id']);
            $table->dropColumn(['article_category_id', 'meta_title', 'meta_description', 'keywords', 'is_featured']);
        });
    }
};
