<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('sms_settings', function (Blueprint $table) {
            $table->id();
            $table->string('provider')->default('twilio'); // Default SMS provider
            $table->string('sid')->nullable(); // Twilio SID or equivalent
            $table->string('auth_token')->nullable(); // API authentication token
            $table->string('phone_number')->nullable(); // Sender phone number
            $table->boolean('enabled')->default(false); // SMS service enabled or not
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sms_settings');
    }
};
