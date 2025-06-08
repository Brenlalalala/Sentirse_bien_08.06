<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientePagoController extends Controller
{
    public function formulario()
    {
        return view('cliente.PagoFormulario');
    }

    public function procesar(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tarjeta' => 'required|digits:16',
            'fecha_exp' => 'required|string|max:5',
            'cvv' => 'required|digits:3',
        ]);

        // Simular procesamiento del pago...

        return redirect()->route('cliente.PagoFormulario')->with('success', 'Pago procesado correctamente.');
    }
}
