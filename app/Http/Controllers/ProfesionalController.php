<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turno;
use App\Models\User;
use App\Models\Historial;
use App\Models\Servicio;
use Illuminate\Support\Facades\Auth;  
use Barryvdh\DomPDF\Facade\Pdf; 
 use Illuminate\Support\Facades\DB;
 use Carbon\Carbon;



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

  

public function verHistorial(Request $request)
{
    $cliente = null;
    $clientes = collect();
    $turnosRealizados = collect();
    $historial = collect();
    $turnosPendientesDisponibles = collect();
    $hoy = Carbon::now('America/Argentina/Buenos_Aires')->toDateString();

    if ($request->filled('cliente_nombre')) {
        $clientes = User::where('name', 'LIKE', '%' . $request->cliente_nombre . '%')
                        ->whereHas('roles', fn($q) => $q->where('name', 'cliente'))
                        ->get();

        if ($clientes->count() === 1) {
            $cliente = $clientes->first();
        } elseif ($request->filled('cliente_id')) {
            $cliente = User::find($request->cliente_id);
        }
    }

    if ($cliente) {
        $turnosRealizados = Turno::with(['servicio', 'profesional'])
            ->where('user_id', $cliente->id)
            ->where('estado', 'realizado')
            ->orderByDesc('fecha')
            ->get();

        $historial = Historial::with('profesional', 'servicio')
            ->where('user_id', $cliente->id)
            ->latest()
            ->get();

         // ✅ Turnos pendientes disponibles para cargar al historial
        $turnosPendientesDisponibles = Turno::with('servicio')
            ->where('user_id', $cliente->id)
            ->where('estado', 'pendiente')
           ->whereDate('fecha', '<=', $hoy) //solo los turnos de hoy o anteriores
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();
        
    }

    return view('profesionales.historial', compact(
        'cliente',
        'clientes',
        'turnosRealizados',
        'historial',
        'turnosPendientesDisponibles'
    ));
}





public function agregarHistorial(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'turno_id' => 'required|exists:turnos,id',
        'detalle' => 'required|string',
    ]);

    $turno = Turno::with('servicio')->findOrFail($request->turno_id);

    // Crear historial
    Historial::create([
        'user_id' => $request->user_id,
        'servicio_id' => $turno->servicio_id,
        'profesional_id' => $turno->profesional_id,
        'detalle' => $request->detalle,
    ]);

    // Marcar turno como realizado
    $turno->estado = 'realizado';
    $turno->save();

    return redirect()->back()->with('success', 'Servicio agregado al historial correctamente.');
}



    public function verTurnos(Request $request)
{
    $profesionalId = Auth::id();

    $query = Turno::with(['user', 'servicio'])
        ->where('profesional_id', $profesionalId);

    if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
        $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
    }

    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }

    if ($request->filled('servicio_id')) {
        $query->where('servicio_id', $request->servicio_id);
    }

    $turnos = $query->orderBy('fecha')
                    ->orderBy('hora')
                    ->get()
                    ->groupBy(function ($turno) {
                        return $turno->fecha . '|' . ($turno->servicio->nombre ?? 'Sin servicio');
                    });

    $servicios = Servicio::orderBy('nombre')->get();

    return view('profesionales.turnos-filtrados', compact('turnos', 'servicios'));
}


public function exportarTurnosPdf(Request $request)
{
$profesionalId = Auth::id();
$profesional = Auth::user();

    $query = Turno::with(['user', 'servicio'])
        ->where('profesional_id', $profesionalId);

    if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
        $query->whereBetween('fecha', [$request->fecha_inicio, $request->fecha_fin]);
    }

    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }

    if ($request->filled('servicio_id')) {
        $query->where('servicio_id', $request->servicio_id);
    }

    $turnos = $query->orderBy('fecha')
                    ->orderBy('hora')
                    ->get()
                    ->groupBy(function ($turno) {
                        return $turno->fecha . '|' . ($turno->servicio->nombre ?? 'Sin servicio');
                    });

    $pdf = Pdf::loadView('profesionales.imprimir', [
    'turnos' => $turnos,
    'profesional' => $profesional,
]);
    return $pdf->download('mis-turnos.pdf');
}


public function agregarDesdeTurno(Request $request)
{
    $request->validate([
        'turno_id' => 'required|exists:turnos,id',
        'user_id' => 'required|exists:users,id',
    ]);

    $turno = Turno::findOrFail($request->turno_id);

    // Solo puede agregar si es el profesional asignado
    if ($turno->profesional_id !== Auth::id()) {
        abort(403);
    }

    // Crear entrada en historial
    Historial::create([
        'user_id' => $request->user_id,
        'profesional_id' => Auth::id(),
        'detalle' => $turno->servicio->nombre . ' realizado el ' . $turno->fecha . ' a las ' . $turno->hora,
    ]);

    // Cambiar estado del turno a realizado
    $turno->estado = 'realizado';
    $turno->save();

    return back()->with('success', 'Servicio agregado al historial correctamente.');
}


}
