<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        // Validación
        if (!Auth::attempt($request->only(['dni', 'password']))) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        // Respuesta
        return response()->json([
            'message' => 'Logged in successfully.',
            'access_token' => auth()->user()->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer'
        ], 201);
    }

    public function logout()
    {
        //Cuando ya este más desarrollado el FrontEnd eliminar solo el token que corresponda a ese navegador
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully.'], 200);
    }

    public function changePassword(Request $request)
    {
        $validatedData = $request->validate([
            'current_password' => 'required|string',
            'password' => ['required', 'string', 'confirmed', 'different:current_password', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],
        ]);

        $user = auth()->user();

        if (!Hash::check($validatedData['current_password'], $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 401);
        }

        $user->password = Hash::make($validatedData['password']);
        $user->save();

        return response()->json(['message' => 'Password changed successfully.'], 200);
    }
}
