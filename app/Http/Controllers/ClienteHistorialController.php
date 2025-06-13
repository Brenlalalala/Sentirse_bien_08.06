<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Turno;

class ClienteHistorialController extends Controller
{
    public function index()
    {
        $turnos = Turno::where('user_id', Auth::id())
            ->with('servicio')
            ->orderByDesc('fecha')
            ->orderByDesc('hora')
            ->get();

        return view('cliente.historial', compact('turnos'));
    }
}
