<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ContactPatientController;
use App\Http\Controllers\FollowUpController;
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
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);//post en vez de put porque no es una actualización completa del recurso

    //Pacientes
    Route::get('/patients', [PatientController::class, 'index']);
    Route::post('/patients', [PatientController::class, 'store']);
    Route::get('/patients/{patient}', [PatientController::class, 'show']);
    Route::put('/patients/{patient}', [PatientController::class, 'update']);
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy']);

    //Seguimientos (no se pueden actualizar ni crear porque se crean solos cuando un paciente se escapa)
    Route::get('/follow-ups', [FollowUpController::class, 'index']);
    Route::get('/follow-ups/{follow-up}', [FollowUpController::class, 'show']);

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
    Route::post('/medication-patient/attach', [MedicationPatientController::class, 'attach']);
    Route::post('/medication-patient/detach', [MedicationPatientController::class, 'detach']);

    //Tabla pivote contact_patient
    Route::post('/contact-patient/attach', [ContactPatientController::class, 'attach']);
    Route::post('/contact-patient/detach', [ContactPatientController::class, 'detach']);

    //Rutas a las que solo tienen acceso los admins
    Route::middleware("role:admin")->group(function () {
        //Usuarios
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);

        //Logs Pacientes (no se pueden actualizar ni crear porque se crean solas al modificar un paciente)
        Route::get('/patient-logs', [PatientLogController::class, 'index']);
        Route::get('/patient-logs/{patient-log}', [PatientLogController::class, 'show']);
        Route::delete('/patient-logs/{patient-log}', [PatientLogController::class, 'destroy']);

        //Seguimientos (solo los admins pueden eliminar)
        Route::delete('/follow-ups/{follow-up}', [FollowUpController::class, 'destroy']);
    });
});
