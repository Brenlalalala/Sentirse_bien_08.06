<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turno;
use App\Models\Servicio;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class TurnosPorDiaController extends Controller
{
    public function index(Request $request)
    {
        // Si no se pasa rango, usar hoy
        $fechaInicio = $request->input('fecha_inicio', now()->toDateString());
        $fechaFin = $request->input('fecha_fin', $fechaInicio);

        $query = Turno::with(['user', 'servicio', 'profesional'])
            ->whereBetween('fecha', [Carbon::parse($fechaInicio), Carbon::parse($fechaFin)]);

        if ($request->filled('servicio_id')) {
            $query->where('servicio_id', $request->input('servicio_id'));
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        if ($request->filled('profesional_id')) {
            $query->where('profesional_id', $request->input('profesional_id'));
        }

        if ($request->filled('cliente_nombre')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('cliente_nombre') . '%');
            });
        }

        $turnos = $query->orderBy('fecha')
                ->orderBy('servicio_id')
                ->orderBy('hora')
                ->get()
                ->groupBy(function($turno) {
                    return $turno->fecha . '|' . ($turno->servicio->nombre ?? 'Sin servicio');
        });

        $servicios = Servicio::orderBy('nombre')->get();
        $profesionales = User::role('profesional')->orderBy('name')->get(); // si usás spatie/laravel-permission

        return view('admin.turnos.turnos_por_dia', compact(
            'turnos',
            'fechaInicio',
            'fechaFin',
            'servicios',
            'profesionales',
            'request'
        ));
    }

        public function imprimir(Request $request)
    {
        // Obtener los filtros aplicados
        $fechaInicio = $request->input('fecha_inicio', now()->toDateString());
        $fechaFin = $request->input('fecha_fin', $fechaInicio);

        // Consultar los turnos filtrados
        $query = Turno::with(['user', 'servicio', 'profesional'])
            ->whereBetween('fecha', [Carbon::parse($fechaInicio), Carbon::parse($fechaFin)]);

        if ($request->filled('servicio_id')) {
            $query->where('servicio_id', $request->input('servicio_id'));
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        if ($request->filled('profesional_id')) {
            $query->where('profesional_id', $request->input('profesional_id'));
        }

        if ($request->filled('cliente_nombre')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('cliente_nombre') . '%');
            });
        }

        $turnos = $query->orderBy('fecha')
                        ->orderBy('hora')
                        ->get()
                        ->groupBy(fn($t) => $t->fecha . ' - ' . ($t->servicio->nombre ?? 'N/A'));

        // Generar el PDF
        $pdf = PDF::loadView('admin.turnos.imprimir', compact('turnos', 'fechaInicio', 'fechaFin', 'request'));

        // Descargar el PDF
        return $pdf->download('turnos_filtrados.pdf');
    }

}
