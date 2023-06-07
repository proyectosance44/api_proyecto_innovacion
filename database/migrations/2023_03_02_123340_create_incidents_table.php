<?php

declare(strict_types=1);

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
        Schema::create('incidences', function (Blueprint $table) {
            $table->id();
            $table->string('patient_dni', 9);
            $table->timestamp('fecha')->useCurrent();
            $table->unsignedMediumInteger('duracion')->nullable();// puede ser null si la incidencia esta en curso
            $table->json('recorrido_paciente');

            $table->foreign('patient_dni')->references('dni')->on('patients')
                ->onDelete("cascade")->onUpdate("cascade");

            $table->unique(['patient_dni', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidences');
    }
};
