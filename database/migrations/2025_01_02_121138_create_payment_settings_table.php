<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->unique(); // e.g., 'stripe', 'paypal'
            $table->string('public_key')->nullable();
            $table->string('secret_key')->nullable();
            $table->boolean('enabled')->default(false); // Add 'enabled' column, default is true
            $table->enum('mode', ['sandbox', 'live'])->default('sandbox');  // 'sandbox' or 'live' mode
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
