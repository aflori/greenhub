<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products_has_blog_articles', function (Blueprint $table) {
            $table->uuid('product_id')->constrained();
            $table->uuid('blog_article_id')->constrained();

            $table->primary(['product_id', 'blog_article_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_has_blog_articles');
    }
};
