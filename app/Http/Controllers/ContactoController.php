<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ContactoController extends Controller
{
    public function enviar(Request $request)
    {
        try {
            // Validación con mensajes personalizados (opcionales)
            $request->validate([
                'nombre' => 'required',
                'mensaje' => 'required',
                'telefono' => ['nullable', Rule::requiredIf(function () use ($request) {
                    return $request->telefono !== null;
                }), 'digits:10'],
            ], [
                'nombre.required' => 'El nombre es obligatorio.',
                'mensaje.required' => 'El mensaje es obligatorio.',
                'telefono.digits' => 'El número de celular debe tener exactamente 10 dígitos.',
            ]);

            // Guardar en la base de datos
            DB::table('contactos')->insert([
                'nombre' => $request->nombre,
                'telefono' => $request->telefono,
                'mensaje' => $request->mensaje,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Respuesta exitosa
            return response()->json(['success' => true, 'message' => 'Mensaje recibido, nos contactaremos a la brevedad.']);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Captura y muestra el primer mensaje de error
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->first()[0]
            ]);
        } catch (\Exception $e) {
            // Otro tipo de error
            return response()->json(['success' => false, 'message' => 'Mensaje NO enviado. Error: ' . $e->getMessage()]);
        }
    }
}
