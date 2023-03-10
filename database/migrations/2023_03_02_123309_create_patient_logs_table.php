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
        Schema::create('patient_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();// Puede ser nulo por si por cualquier cosa no se puede determinar que usuario realizó la acción
            $table->string('patient_dni', 9);
            $table->timestamp('fecha')->useCurrent();
            $table->enum('accion', ['creación', 'modificación', 'borrado lógico', 'restauración', 'borrado físico']);
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete("cascade")->onUpdate("cascade");
            $table->foreign('patient_dni')->references('dni')->on('patients')->onDelete("cascade")->onUpdate("cascade");
            $table->unique(['user_id', 'patient_dni', 'fecha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modifications');
    }
};
