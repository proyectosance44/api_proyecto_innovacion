<?php

declare(strict_types=1);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactPatientController;
use App\Http\Controllers\IncidenceController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\MedicationPatientController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientLogController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//Login es la única ruta en la que no hace falta estar autenticado
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    //Rutas auth
    Route::put('/change-password', [AuthController::class, 'changePassword']);
    Route::delete('/logout', [AuthController::class, 'logout']);

    //Pacientes
    Route::get('/patients', [PatientController::class, 'index']);
    Route::post('/patients', [PatientController::class, 'store']);
    Route::get('/patients/{patient}', [PatientController::class, 'show']);
    Route::put('/patients/{patient}', [PatientController::class, 'update']);
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy']);

    //Incidencias (solo se pueden consultar porque se crean cuando un paciente se escapa)
    Route::get('/incidences', [IncidenceController::class, 'index']);
    Route::get('/incidences/in_progress', [IncidenceController::class, 'inProgress']);
    Route::get('/incidences/{incidence}', [IncidenceController::class, 'show']);

    //Medicaciones
    Route::get('/medications', [MedicationController::class, 'index']);
    Route::post('/medications', [MedicationController::class, 'store']);
    Route::get('/medications/{medication}', [MedicationController::class, 'show']);
    Route::put('/medications/{medication}', [MedicationController::class, 'update']);
    Route::delete('/medications/{medication}', [MedicationController::class, 'destroy']);

    //Contactos
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::post('/contacts', [ContactController::class, 'store']);
    Route::get('/contacts/{contact}', [ContactController::class, 'show']);
    Route::put('/contacts/{contact}', [ContactController::class, 'update']);
    Route::delete('/contacts/{contact}', [ContactController::class, 'destroy']);

    //Tabla pivote medication_patient
    Route::post('/medication-patient', [MedicationPatientController::class, 'attach']);
    Route::put('/medication-patient/{medication}/{patient}', [MedicationPatientController::class, 'update']);
    Route::delete('/medication-patient/{medication}/{patient}', [MedicationPatientController::class, 'detach']);

    //Tabla pivote contact_patient
    Route::post('/contact-patient', [ContactPatientController::class, 'attach']);
    Route::put('/contact-patient/{contact}/{patient}', [ContactPatientController::class, 'update']);
    Route::delete('/contact-patient/{contact}/{patient}', [ContactPatientController::class, 'detach']);

    //Para actualizar el perfil del usuario
    Route::get('/user-profile', [UserController::class, 'getProfile']);
    Route::put('/user-profile', [UserController::class, 'updateProfile']);

    //Rutas a las que solo tienen acceso los admins
    Route::middleware("role:admin")->group(function () {
        //Usuarios
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);

        //Logs Pacientes (solo se pueden consultar porque se crean solos al modificar un paciente)
        Route::get('/patient-logs', [PatientLogController::class, 'index']);
        Route::get('/patient-logs/{patientLog}', [PatientLogController::class, 'show']);
    });
});
