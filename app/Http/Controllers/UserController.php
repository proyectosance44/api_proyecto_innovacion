<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\Dni;
use App\Rules\NotBlank;
use App\Rules\Telefono;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' => 'Usuarios obtenidos exitosamente.',
            'users' => User::all()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación
        $validatedData = $request->validate([
            'dni' => ['required', 'string', 'size:9', new Dni()],
            'name' => ['required', 'string', 'max:255', new NotBlank()],
            'apellidos' => ['required', 'string', 'max:255', new NotBlank()],
            'rol' => 'required|string|in:admin,trabajador',
            'email' => 'required|string|email|max:255',
            'telefono' => ['required', 'string', 'size:9', new Telefono()],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()]
        ]);

        // Si ha sido borrado lógicamente se restaura
        $user = User::onlyTrashed()->where('dni', $validatedData['dni'])->first();
        $isTrashed = false;
        if (isset($user)) {
            $request->validate([
                'dni' => [Rule::unique('users')->ignore($user->id)],
                'email' => [Rule::unique('users')->ignore($user->id)],
                'telefono' => [Rule::unique('users')->ignore($user->id)],
            ]);
            $isTrashed = true;
        } else {
            $request->validate([
                'dni' => 'unique:users',
                'email' => 'unique:users',
                'telefono' => 'unique:users',
            ]);
            $user = new User();
        }

        // Creación
        $user->dni = $validatedData['dni'];
        $user->name = $validatedData['name'];
        $user->apellidos = $validatedData['apellidos'];
        $user->rol = $validatedData['rol'];
        $user->email = $validatedData['email'];
        $user->telefono = $validatedData['telefono'];
        $user->password = Hash::make($validatedData['password']);
        if ($isTrashed) {
            $user->restore();
        } else {
            $user->save();
        }

        // Respuesta
        return response()->json([
            'message' => 'Usuario creado exitosamente.',
            'user' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json([
            'message' => 'Usuario obtenido exitosamente.',
            'user' => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'dni' => ['string', 'size:9', new Dni(), Rule::unique('users')->ignore($user->id)],
            'name' => ['string', 'max:255', new NotBlank()],
            'apellidos' => ['string', 'max:255', new NotBlank()],
            'rol' => 'string|in:admin,trabajador',
            'email' => ['string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'telefono' => ['string', 'size:9', new Telefono(), Rule::unique('users')->ignore($user->id)],
            'password' => ['string', 'confirmed', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()]
        ]);

        if (isset($validatedData['dni'])) $user->dni = $validatedData['dni'];
        if (isset($validatedData['name'])) $user->name = $validatedData['name'];
        if (isset($validatedData['apellidos'])) $user->apellidos = $validatedData['apellidos'];
        if (isset($validatedData['rol'])) $user->rol = $validatedData['rol'];
        if (isset($validatedData['email'])) $user->email = $validatedData['email'];
        if (isset($validatedData['telefono'])) $user->telefono = $validatedData['telefono'];
        if (isset($validatedData['password'])) $user->password = Hash::make($validatedData['password']);
        $user->save();

        return response()->json([
            'message' => 'Usuario actualizado exitosamente.',
            'user' => $user
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'Usuario eliminado exitosamente',
            'user' => $user
        ], 200);
    }
}
