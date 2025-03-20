<?php
// database/migrations/xxxx_xx_xx_create_general_customizes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralCustomizesTable extends Migration
{
    public function up()
    {
        Schema::create('general_customizes', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('general_customizes');
    }
}
