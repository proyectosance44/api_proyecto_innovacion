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
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->string('patient_dni', 9);
            $table->timestamp('fecha')->useCurrent();
            $table->unsignedBigInteger('num_salida');
            $table->double('lat');
            $table->double('lng');

            $table->foreign('patient_dni')->references('dni')->on('patients')->onDelete("cascade")->onUpdate("cascade");
            $table->unique(['patient_dni', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};
