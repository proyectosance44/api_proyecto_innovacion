<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Services\DataProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private DataProcessingService $dataProcessor;

    public function __construct(DataProcessingService $dataProcessor)
    {
        $this->dataProcessor = $dataProcessor;
    }

    public function login(AuthRequest $request)
    {
        if (!Auth::attempt($request->only(['dni', 'password']))) {
            return response()->json([
                'message' => 'Credenciales no válidas.'
            ], 401);
        }

        return response()->json([
            'message' => 'Inicio de sesión exitoso.',
            'access_token' => auth()->user()->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer'
        ], 201);
    }

    public function logout()
    {
        //Actualmente al cerrar sesión se cierra sesión de todos los dispositivos porque se borran todos los tokens.
        auth()->user()->tokens()->delete();
        return response()->json([
            'message' => 'Cierre de sesión exitoso.'
        ], 200);
    }

    public function changePassword(AuthRequest $request)
    {
        auth()->user()->update($this->dataProcessor->processData(['password' => $request->validated()['password']]));
        return response()->json([
            'message' => 'Contraseña modificada exitosamente.'
        ], 200);
    }
}
