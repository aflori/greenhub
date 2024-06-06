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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 50);
            $table->float('price', 7, 2);
            $table->float('vat_rate', 3, 2);
            $table->integer('stock');
            $table->string('description', 1000);
            $table->integer('environmental_impact');
            $table->string('origin', 50);
            $table->string('measuring_unit', 20)->nullable();
            $table->float('measure', 8, 2)->nullable();
            $table->uuid('discount_id')->nullable()->constrained();
            $table->uuid('brand_id')->constrained();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
