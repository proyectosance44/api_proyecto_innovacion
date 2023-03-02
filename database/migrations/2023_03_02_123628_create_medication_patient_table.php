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
        Schema::create('medication_patient', function (Blueprint $table) {
            $table->id();
            $table->string('patient_dni', 9);
            $table->string('medication_codigo');
            $table->boolean('urgente');

            $table->foreign('patient_dni')->references('dni')->on('patients')->onDelete("cascade")->onUpdate("cascade");
            $table->foreign('medication_codigo')->references('codigo')->on('medications')->onDelete("cascade")->onUpdate("cascade");
            $table->unique(['patient_dni', 'medication_codigo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_patient');
    }
};
