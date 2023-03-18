<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use App\Rules\Dni;
use App\Rules\NotBlank;
use Illuminate\Validation\Rule;

//¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡¡Implementar las fotos!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' => 'Pacientes obtenidos exitosamente.',
            'patients' => Patient::all()
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
            'id_lora' => ['required', 'string', 'max:255', 'regex:/^([1-9a-f][0-9a-f]*)$/i'],
            'id_rfid' => ['required', 'string', 'max:255', 'regex:/^([1-9a-f][0-9a-f]*)$/i'],
            'nombre' => ['required', 'string', 'max:255', new NotBlank()],
            'apellidos' => ['required', 'string', 'max:255', new NotBlank()],
            //'foto' => 'image|mimes:jpg,png,jpeg|max:16384' // 16 megabytes
        ]);

        // Si ha sido borrado lógicamente se restaura
        $patient = Patient::onlyTrashed()->where('dni', $validatedData['dni'])->first();
        $isTrashed = false;
        if (isset($patient)) {
            $request->validate([
                'dni' => [Rule::unique('patients')->ignore($patient->dni, 'dni')],
                'id_lora' => [Rule::unique('patients')->ignore($patient->dni, 'dni')],
                'id_rfid' => [Rule::unique('patients')->ignore($patient->dni, 'dni')]
            ]);
            $isTrashed = true;
        } else {
            $request->validate([
                'dni' => 'unique:patients',
                'id_lora' => 'unique:patients',
                'id_rfid' => 'unique:patients',
            ]);
            $patient = new Patient();
        }

        // Creación del paciente
        $patient->dni = $validatedData['dni'];
        $patient->id_lora = $validatedData['id_lora'];
        $patient->id_rfid = $validatedData['id_rfid'];
        $patient->nombre = $validatedData['nombre'];
        $patient->apellidos = $validatedData['apellidos'];
        //$patient->ruta_foto = $request->file('foto')->storeAs('public/imgs', $patient->dni . '.' . $request->file('foto')->getClientOriginalExtension());
        if ($isTrashed) {
            $patient->restore();
        } else {
            $patient->save();
        }

        // Respuesta
        return response()->json([
            'message' => 'Paciente creado exitosamente.',
            'patient' => $patient
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return response()->json([
            'message' => 'Paciente obtenido exitosamente.',
            'patient' => $patient
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        dd($request->all());
        $validatedData = $request->validate([
            'dni' => ['string', 'size:9', new Dni(), Rule::unique('patients')->ignore($patient->dni, 'dni')],
            'id_lora' => ['string', 'max:255', 'regex:/^([1-9a-f][0-9a-f]*)$/i', Rule::unique('patients')->ignore($patient->dni, 'dni')],
            'id_rfid' => ['string', 'max:255', 'regex:/^([1-9a-f][0-9a-f]*)$/i', Rule::unique('patients')->ignore($patient->dni, 'dni')],
            'nombre' => ['string', 'max:255', new NotBlank()],
            'apellidos' => ['string', 'max:255', new NotBlank()],
            //'foto' => 'image|mimes:jpg,png,jpeg|max:16384' // 16 megabytes
        ]);

        if (isset($validatedData['dni'])) $patient->dni = $validatedData['dni'];
        if (isset($validatedData['id_lora'])) $patient->id_lora = $validatedData['id_lora'];
        if (isset($validatedData['id_rfid'])) $patient->id_rfid = $validatedData['id_rfid'];
        if (isset($validatedData['nombre'])) $patient->nombre = $validatedData['nombre'];
        if (isset($validatedData['apellidos'])) $patient->apellidos = $validatedData['apellidos'];
        //if (isset($validatedData['foto'])) $patient->ruta_foto = $request->file('foto')->storeAs('public/imgs', $patient->dni . '.' . $request->file('foto')->getClientOriginalExtension());
        $patient->save();

        return response()->json([
            'message' => 'Paciente actualizado exitosamente.',
            'patient' => $patient
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();

        return response()->json([
            'message' => 'Paciente eliminado exitosamente',
            'patient' => $patient
        ], 200);
    }
}
