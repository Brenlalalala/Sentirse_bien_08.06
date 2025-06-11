<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reserva;
use App\Models\Turno;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

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
            abort(403, 'Debes iniciar sesión para reservar.');
        }

        $turno = new Turno();
        $turno->user_id = Auth::id();
        $turno->servicio_id = $request->servicio_id;
        $turno->fecha = $request->fecha;
        $turno->hora = $request->hora;
        $turno->save();

        return redirect()->route('turnos.lista')->with('success', '✅ ¡Te estaremos contactando por mail para confirmar tu turno!');
    }
}
