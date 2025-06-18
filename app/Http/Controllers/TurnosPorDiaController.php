<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turno;
use App\Models\Servicio;
use Barryvdh\DomPDF\Facade\Pdf;


class TurnosPorDiaController extends Controller
{
    public function index(Request $request)
    {
        $fechaInicio = $request->input('fecha_inicio');
        $fechaFin = $request->input('fecha_fin');

        $query = Turno::with(['user', 'servicio']);

        if ($fechaInicio && $fechaFin) {
            $query->whereBetween('fecha', [$fechaInicio, $fechaFin]);
        } elseif ($fechaInicio) {
            $query->where('fecha', '>=', $fechaInicio);
        } elseif ($fechaFin) {
            $query->where('fecha', '<=', $fechaFin);
        } else {
            // Si no hay fechas, que muestre por defecto el día de hoy
            $query->whereDate('fecha', now()->toDateString());
        }

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

        // Si se solicitó exportar a PDF
        if ($request->has('exportar_pdf')) {
            $pdf = Pdf::loadView('admin.turnos.turnos_pdf', [
                'turnos' => $turnos,
                'fechaInicio' => $fechaInicio,
                'fechaFin' => $fechaFin
            ]);
            return $pdf->download('turnos.pdf');
        }

        return view('admin.turnos.turnos_por_dia', [
            'turnos' => $turnos,
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'servicios' => $servicios
        ]);
    }
}