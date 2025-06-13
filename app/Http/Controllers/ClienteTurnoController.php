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

    public function crearTurno($clienteId, $servicioId, $fecha, $hora)
    {
        $servicio = Servicio::findOrFail($servicioId);
        $precioOriginal = $servicio->precio;

        // Verificar si aplica descuento
        $fechaServicio = new \DateTime($fecha . ' ' . $hora);
        $ahora = new \DateTime();
        $intervalo = $ahora->diff($fechaServicio);
        $horasHastaServicio = ($intervalo->days * 24) + $intervalo->h;

        $descuento = 0;
        if ($horasHastaServicio >= 48) {
            $descuento = 0.15; // Aplicar descuento del 15%
        }

        // Calcular el precio final
        $precioFinal = $precioOriginal * (1 - $descuento);

        // Crear el turno
        return Turno::create([
            'user_id' => $clienteId,
            'servicio_id' => $servicioId,
            'profesional_id' => 5, // ID del profesional por defecto
            'fecha' => $fecha,
            'hora' => $hora,
            'monto' => $precioFinal,  // Guardar el monto con descuento
            'estado' => 'pendiente',
        ]);
    }

    public function misServicios()
    {
        $cliente = Auth::user();

        $turnosPendientes = Turno::with('servicio')
            ->where('user_id', $cliente->id)
            ->where('estado', 'pendiente')
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
            ->where('estado', 'realizado')
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
