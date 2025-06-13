<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Turno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClienteTurnoController extends Controller
{
    public function create()
    {
        $servicios = Servicio::all();
        return view('clientes.reservar-turno', compact('servicios'));
    }

    public function store(Request $request)
    {
        $serviciosSeleccionados = [];

        // Filtrar servicios seleccionados
        foreach ($request->input('servicios', []) as $servicio_id => $data) {
            if (isset($data['seleccionado']) && $data['seleccionado']) {
                $serviciosSeleccionados[$servicio_id] = $data;
            }
        }

        if (empty($serviciosSeleccionados)) {
            return redirect()->back()->withErrors(['Debe seleccionar al menos un servicio.'])->withInput();
        }

        // Validación
        foreach ($serviciosSeleccionados as $servicio_id => $data) {
            $request->validate([
                "servicios.$servicio_id.fecha" => 'required|date|after_or_equal:today',
                "servicios.$servicio_id.hora" => 'required|date_format:H:i',
            ]);
        }

        $request->validate([
            'metodo_pago' => 'required',
        ]);

        foreach ($serviciosSeleccionados as $servicio_id => $data) {
            $servicio = Servicio::findOrFail($servicio_id);

            Turno::create([
                'user_id'      => Auth::id(),
                'servicio_id'  => $servicio_id,
                'fecha'        => $data['fecha'],
                'hora'         => $data['hora'],
                'metodo_pago'  => $request->input('metodo_pago'),
                'monto'        => $servicio->precio,
                'estado'       => 'pendiente',
            ]);
        }

        return redirect()->route('cliente.mis-servicios')->with('success', 'Reserva realizada con éxito.');
    }

    public function misServicios()
    {
        $cliente = Auth::user();

        $turnosPendientes = Turno::with('servicio')
            ->where('user_id', $cliente->id)
            ->where(function ($query) {
                $query->where('estado', 'pendiente')
                      ->orWhere(function ($q) {
                          $q->where('fecha', '>=', now()->toDateString());
                      });
            })
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        return view('clientes.mis-servicios', compact('turnosPendientes'));
    }

    public function historial()
    {
        $cliente = Auth::user();

        $turnosRealizados = Turno::with('servicio')
            ->where('user_id', $cliente->id)
            ->where(function ($query) {
                $query->where('estado', 'realizado')
                      ->orWhere(function ($q) {
                          $q->where('fecha', '<', now()->toDateString());
                      });
            })
            ->orderByDesc('fecha')
            ->orderByDesc('hora')
            ->get();

        return view('clientes.historial', compact('turnosRealizados'));
    }

    public function cancelar(Turno $turno)
    {
        if ($turno->user_id !== Auth::id()) {
            abort(403);
        }

        if ($turno->estado === 'pendiente') {
            $turno->delete();
            return redirect()->route('cliente.mis-servicios')->with('success', 'Turno cancelado.');
        }

        return redirect()->route('cliente.mis-servicios')->with('error', 'No se puede cancelar este turno.');
    }
}
