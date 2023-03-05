<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\Dni;
use App\Rules\NotBlank;
use App\Rules\Telefono;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación
        $validatedData = $request->validate([
            'dni' => ['required', 'string', 'size:9', new Dni(), 'unique:users'],
            'name' => ['required', 'string', 'max:255', new NotBlank()],
            'apellidos' => ['required', 'string', 'max:255', new NotBlank()],
            'rol' => 'required|string|in:admin,trabajador',
            'email' => 'required|string|email|max:255|unique:users',
            'telefono' => ['required', 'string', 'size:9', new Telefono(), 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()]
        ]);

        // Creación
        $user = new User();
        $user->dni = $validatedData['dni'];
        $user->name = $validatedData['name'];
        $user->apellidos = $validatedData['apellidos'];
        $user->rol = $validatedData['rol'];
        $user->email = $validatedData['email'];
        $user->telefono = $validatedData['telefono'];
        $user->password = Hash::make($validatedData['password']);
        $user->save();

        // Respuesta
        return response()->json([
            'message' => 'Usuario creado exitosamente',
            'user' => $user
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
