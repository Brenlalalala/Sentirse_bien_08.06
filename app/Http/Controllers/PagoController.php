<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Pago;
use App\Models\User;
use App\Models\Servicio;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PagoController extends Controller
{
    public function porServicio(Request $request)
    {
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        $query = DB::table('pagos')
            ->join('turnos', 'pagos.id', '=', 'turnos.pago_id')
            ->join('servicios', 'turnos.servicio_id', '=', 'servicios.id')
            ->select('servicios.nombre as servicio', DB::raw('SUM(pagos.monto) as total'));

        if (Auth::user()->es_admin) {
            $query->when($desde, fn($q) => $q->whereDate('pagos.fecha_pago', '>=', $desde))
                  ->when($hasta, fn($q) => $q->whereDate('pagos.fecha_pago', '<=', $hasta));
        } else {
            $query->where('pagos.user_id', Auth::id())
                  ->when($desde, fn($q) => $q->whereDate('pagos.fecha_pago', '>=', $desde))
                  ->when($hasta, fn($q) => $q->whereDate('pagos.fecha_pago', '<=', $hasta));
        }

        $resultados = $query->groupBy('servicios.nombre')->get();

        return view('pagos.por-servicio', compact('resultados', 'desde', 'hasta'));
    }

    public function porProfesional(Request $request)
    {
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        $query = DB::table('pagos')
            ->join('turnos', 'pagos.id', '=', 'turnos.pago_id')
            ->join('users', 'turnos.profesional_id', '=', 'users.id')
            ->select('users.name as profesional', DB::raw('SUM(pagos.monto) as total'));

        if (Auth::user()->es_admin) {
            $query->when($desde, fn($q) => $q->whereDate('pagos.fecha_pago', '>=', $desde))
                  ->when($hasta, fn($q) => $q->whereDate('pagos.fecha_pago', '<=', $hasta));
        } else {
            $query->where('pagos.user_id', Auth::id())
                  ->when($desde, fn($q) => $q->whereDate('pagos.fecha_pago', '>=', $desde))
                  ->when($hasta, fn($q) => $q->whereDate('pagos.fecha_pago', '<=', $hasta));
        }

        $resultados = $query->groupBy('users.name')->get();

        return view('pagos.por-profesional', compact('resultados', 'desde', 'hasta'));
    }

    //para cliente
    public function misPagos()
    {
        $userId = Auth::id();

        // Obtener pagos solo del cliente logueado, ordenados por fecha de pago descendente
        $pagos = Pago::where('user_id', $userId)
            ->orderBy('fecha_pago', 'desc')
            ->paginate(10);

        return view('clientes.mis-pagos', compact('pagos'));
    }


    public function exportarPagosPorProfesional(Request $request)
{
    $desde = $request->input('desde');
    $hasta = $request->input('hasta');

    $pagos = Pago::with(['user', 'turnos.servicio'])
        ->whereBetween('fecha_pago', [$desde, $hasta])
        ->get()
        ->groupBy(function ($pago) {
            return $pago->turnos->first()?->profesional->name ?? 'Sin Profesional';
        });

    $pdf = PDF::loadView('pagos.imprimir_prof', compact('pagos', 'desde', 'hasta'));
    return $pdf->download('pagos_por_profesional.pdf');
}

public function exportarPagosPorServicio(Request $request)
{
    $desde = $request->input('desde');
    $hasta = $request->input('hasta');

    $pagos = Pago::with(['user', 'turnos.servicio'])
        ->whereBetween('fecha_pago', [$desde, $hasta])
        ->get()
        ->groupBy(function ($pago) {
            return $pago->turnos->first()?->servicio->nombre ?? 'Sin Servicio';
        });

    $pdf = Pdf::loadView('pagos.imprimir_scio', compact('pagos', 'desde', 'hasta'));
    return $pdf->download('pagos_por_servicio.pdf');
}


}

