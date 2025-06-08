<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Reserva;

class ReservaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|email',
            'telefono' => 'nullable|string',
            'servicio' => 'required|string',
            'fecha' => 'required|date',
            'hora' => 'required',
        ]);

        $cliente = Cliente::firstOrCreate(
            ['email' => $request->email],
            ['nombre' => $request->nombre, 'telefono' => $request->telefono]
        );

        Reserva::create([
            'cliente_id' => $cliente->id,
            'servicio' => $request->servicio,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
        ]);

        return redirect()->back()->with('success', '✅ ¡Te estaremos contactando por mail para confirmar tu turno!');
    }
}
