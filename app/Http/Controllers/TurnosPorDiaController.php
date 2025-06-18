<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turno;
use App\Models\Servicio;

class TurnosPorDiaController extends Controller
{
    public function index(Request $request)
    {
        $fecha = $request->input('fecha', now()->toDateString());
        $query = Turno::with(['user', 'servicio'])
            ->whereDate('fecha', $fecha);


        if ($request->filled('servicio_id')) {
            $query->where('servicio_id', $request->input('servicio_id'));
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        $turnos = $query->orderBy('servicio_id')
                        ->orderBy('hora')
                        ->get()
                        ->groupBy('servicio.nombre');

        $servicios = Servicio::orderBy('nombre')->get();

        return view('admin.turnos.turnos_por_dia', compact('turnos', 'fecha', 'servicios'));
    }
}
