<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turno;

class TurnosPorDiaController extends Controller
{
    public function index(Request $request)
    {
        // Si no se seleccionó fecha, usar hoy como predeterminado
        $fecha = $request->input('fecha', now()->toDateString());

        $turnos = Turno::with(['user', 'servicio'])
            ->whereDate('fecha', $fecha)
            ->orderBy('hora')
            ->get();

        return view('admin.turnos.turnos_por_dia', compact('turnos', 'fecha'));
    }
}
