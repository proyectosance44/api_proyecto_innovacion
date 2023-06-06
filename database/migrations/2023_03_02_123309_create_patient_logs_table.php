<?php

declare(strict_types=1);

use App\Enums\LogAction;
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
            $table->unsignedBigInteger('user_id')->nullable(); // Puede ser nulo por si por cualquier cosa no se puede determinar que usuario realizó la acción
            $table->string('patient_dni', 9)->nullable();
            $table->timestamp('fecha')->useCurrent();
            $table->enum('accion', [LogAction::Creation->value, LogAction::Modification->value, LogAction::Deleted->value, LogAction::Assignment->value, LogAction::Omission->value]);
            $table->text('descripcion');

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete("set null")->onUpdate("cascade");

            $table->foreign('patient_dni')->references('dni')->on('patients')
                ->onDelete("set null")->onUpdate("cascade");
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
