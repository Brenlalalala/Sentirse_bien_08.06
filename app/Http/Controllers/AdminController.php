<?php

namespace App\Http\Controllers;
use App\Models\Servicio;
use App\Models\Turno;

use Illuminate\Http\Request;
use PDF; // Si usas barryvdh/laravel-dompdf

class AdminController extends Controller
{
    public function index()
    {
        // Retorna la vista para el panel de administración
        return view('admin.panel');
    }

public function servicios()
{
    $servicios = Servicio::all();
    return view('admin.servicios.secciones.servicios', compact('servicios'));
}




public function turnos()
{
    $turnos = Turno::with(['cliente', 'servicio'])->orderBy('fecha')->orderBy('hora')->get();
    return view('admin.servicios.secciones.turnos', compact('turnos'));
}

public function turnosPorDia()
{
    return view('admin.secciones.turnos_por_dia');
}

public function reporteServicios()
{
    return view('admin.secciones.reporte_servicios');
}

public function reporteProfesionales()
{
    return view('admin.secciones.reporte_profesionales');
}
    
}
