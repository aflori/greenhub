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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('number');
            $table->dateTime('order_date');
            $table->dateTime('delivery_date');
            $table->float('bill', 7, 2);
            $table->float('vat_rate', 3, 3);
            $table->float('shipping_fee', 7, 2);
            $table->float('total_price');
            $table->foreignUuid('buyer_id')->constrained(table: 'users');
            $table->foreignUuid('facturation_adress')->constrained(table: 'adresses');
            $table->foreignUuid('delivery_adress')->constrained(table: 'adresses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
