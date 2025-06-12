<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reserva;
use App\Models\Turno;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReservaController extends Controller 
{
    public function __construct()
    {
        $this->middleware('auth'); 
    }

    public function store(Request $request)
    {
        $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date',
            'hora' => 'required'
        ]);

        if (!Auth::check()) {
            abort(403, 'Inicia sesión para reservar.');
        }
        
        Turno::create([
            'user_id' => Auth::id(), // 👈 Este es el que faltaba
            'servicio_id' => $request->servicio_id,
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('turnos.lista')->with('success', '✅ ¡Te estaremos contactando por mail para confirmar tu turno!');
    }
}
