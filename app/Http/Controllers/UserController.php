<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserProfileRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\DataProcessingService;

class UserController extends Controller
{
    private DataProcessingService $dataProcessor;

    public function __construct(DataProcessingService $dataProcessor)
    {
        $this->dataProcessor = $dataProcessor;
    }

    public function index()
    {
        return response()->json([
            'message' => 'Usuarios obtenidos exitosamente.',
            'users' => User::with('patient_logs')->get()
        ], 200);
    }

    public function store(UserRequest $request)
    {
        User::create($this->dataProcessor->processData($request->validated()));
        return response()->json([
            'message' => 'Usuario creado exitosamente.'
        ], 201);
    }

    public function show(User $user)
    {
        return response()->json([
            'message' => 'Usuario obtenido exitosamente.',
            'user' => User::with('patient_logs')->find($user->id)
        ], 200);
    }

    public function update(UserRequest $request, User $user)
    {
        $user->update($this->dataProcessor->processData($request->validated()));
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        return response()->json([
            'message' => 'Usuario actualizado exitosamente.'
        ], 201);
    }

    public function getProfile()
    {
        return response()->json([
            'message' => 'Perfil de usuario obtenido exitosamente.',
            'user' => User::findOrFail(auth()->id())
        ], 201);
    }

    public function updateProfile(UserProfileRequest $request)
    {
        $user = User::findOrFail(auth()->id());
        $validatedData = $this->dataProcessor->processData($request->validated());
        $dataToUpdate = [];

        if (isset($validatedData['telefono']))
            $dataToUpdate['telefono'] =  $validatedData['telefono'];
        if (isset($validatedData['email']))
            $dataToUpdate['email'] =  $validatedData['email'];
        $user->update($dataToUpdate);
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        return response()->json([
            'message' => 'Perfil de usuario actualizado exitosamente.'
        ], 201);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'message' => 'Usuario eliminado exitosamente.'
        ], 200);
    }
}
