<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('email_settings', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('provider'); // For the email provider (e.g., smtp, sendmail)
            $table->string('host')->nullable(); // SMTP server host
            $table->integer('port')->nullable(); // Port for the SMTP server
            $table->string('username')->nullable(); // Username for SMTP
            $table->string('password')->nullable(); // Password for SMTP
            $table->string('encryption')->nullable(); // Encryption type (tls or ssl)
            $table->string('from_address'); // From address (email)
            $table->string('from_name'); // From name
            $table->boolean('enabled'); // Whether the email setting is enabled or not
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_settings');
    }
}
