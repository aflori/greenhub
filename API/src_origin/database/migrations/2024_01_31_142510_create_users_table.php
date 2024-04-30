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
        Schema::create('users', function (Blueprint $table){
            $table->uuid("id")->primary();
            $table->string("pseudoname", 50)->unique();
            $table->string("first_name", 255);
            $table->string("last_name", 255);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string("password");
            // $table->enum("role", ["client", "company", "admin"]); changed into string type for maintainability purpose
            $table->string("role");
            $table->foreignUuid("company_id")->nullable()->constrained()->nullOnDelete();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
