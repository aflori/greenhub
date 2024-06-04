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
        Schema::create('comments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 100);
            $table->string('comment', 500);
            $table->integer('rating')->nullable(); // facultative - for product comment only
            $table->foreignUuid('author_id')->references('id')->on('users');
            $table->string('coment_on_table', 75);
            $table->uuid('table_key'); //either compan_id, blog_article_id or product_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
