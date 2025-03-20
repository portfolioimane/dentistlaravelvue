<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('patient_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->date('treatment_date');
            $table->text('treatment_details');
            $table->string('dentist_name');
            $table->enum('treatment_type', ['consultation', 'filling', 'cleaning', 'extraction', 'root_canal', 'implant', 'orthodontics', 'cosmetic', 'other']);
            $table->decimal('treatment_cost', 8, 2);
            $table->decimal('amount_paid', 8, 2)->default(0.00);
            $table->decimal('remaining_balance', 8, 2)->default(0.00);
            $table->text('prescriptions')->nullable();
            $table->text('follow_up_instructions')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patient_histories');
    }
}
