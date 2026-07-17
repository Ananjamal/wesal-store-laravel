<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('message')->nullable()->after('name');          // رسالة المنتج
            $table->text('short_description')->nullable()->after('message'); // وصف مختصر
            $table->boolean('is_published')->default(true)->after('status'); // حالة النشر
            $table->string('meta_title')->nullable()->after('is_published');
            $table->text('meta_description')->nullable()->after('meta_title');

            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_published']);
            $table->dropColumn(['message', 'short_description', 'is_published', 'meta_title', 'meta_description']);
        });
    }
};
