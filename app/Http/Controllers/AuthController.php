<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ItemNotFoundException;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        // Validación
        if (!Auth::attempt($request->only(['dni', 'password']))) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Respuesta
        return response()->json([
            'message' => 'Logged in successfully',
            'access_token' => auth()->user()->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer'
        ], 201);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        // Respuesta
        return response()->json([
            'message' => 'Logged out successfully'
        ], 200);
    }

    public function changePassword()
    {
    }
}
