<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turno;
use App\Models\User;
use App\Models\Servicio;

class TurnoController extends Controller
{
    public function index(Request $request)
    {
        $turnos = Turno::with(['user', 'servicio'])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        return view('admin.turnos.index', compact('turnos'));
    }
}
