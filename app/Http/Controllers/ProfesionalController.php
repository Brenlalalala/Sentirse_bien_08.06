<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turno;
use App\Models\User;
use App\Models\Historial;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;   


class ProfesionalController extends Controller
{
    public function turnosDelDia()
    {
        $turnos = Turno::with(['usuario', 'servicio'])
            ->whereDate('fecha', now()->toDateString())
            ->where('profesional_id', Auth::id())
            ->get();

        return view('profesionales.turnos-dia', compact('turnos'));
    }

    public function agregarHistorial(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'detalle' => 'required|string|max:1000',
        ]);

        Historial::create([
            'user_id' => $request->user_id,
            'profesional_id' => Auth::id(),
            'detalle' => $request->detalle,
        ]);

        return back()->with('success', 'Historial agregado correctamente.');
    }

    public function verHistorial($id)
    {
        $cliente = User::findOrFail($id);
        $historial = Historial::where('user_id', $id)->latest()->get();

        return view('profesionales.historial', compact('cliente', 'historial'));
    }
}
