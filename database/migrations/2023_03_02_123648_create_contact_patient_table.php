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
        Schema::create('contact_patient', function (Blueprint $table) {
            $table->id();
            $table->string('patient_dni', 9);
            $table->string('contact_dni', 9);
            $table->unsignedTinyInteger('orden_pref');

            $table->foreign('patient_dni')->references('dni')->on('patients')
                ->onDelete("cascade")->onUpdate("cascade");

            $table->foreign('contact_dni')->references('dni')->on('contacts')
                ->onDelete("cascade")->onUpdate("cascade");

            $table->unique(['patient_dni', 'contact_dni']);
            $table->unique(['patient_dni', 'orden_pref']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_patient');
    }
};
