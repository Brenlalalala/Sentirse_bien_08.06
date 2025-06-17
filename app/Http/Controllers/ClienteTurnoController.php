<?php

namespace App\Http\Controllers;


use App\Models\Servicio;
use App\Models\Turno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ComprobantePago;
use App\Http\Controllers\TurnoController;
use App\Models\User;
class ClienteTurnoController extends Controller
{


    public function create()
    {
        $servicios = Servicio::all();
        //Agregué profesionales para la vista de reserva de turno
        $profesionales = User::where('role', 'profesional')->get(); 
        return view('clientes.reservar-turno', compact('servicios', 'profesionales'));
    }


    public function store(Request $request)
    {
        $serviciosSeleccionados = [];

        // Filtrar solo los servicios seleccionados
        foreach ($request->input('servicios', []) as $servicio_id => $data) {
            if (isset($data['seleccionado']) && $data['seleccionado']) {
                $serviciosSeleccionados[$servicio_id] = $data;
            }
        }

        // Si no hay servicios seleccionados, redirigir con error
        if (empty($serviciosSeleccionados)) {
            return redirect()->back()->withErrors(['Debe seleccionar al menos un servicio.'])->withInput();
        }

        // Validar solo los seleccionados
        foreach ($serviciosSeleccionados as $servicio_id => $data) {
            $request->validate([
                "servicios.$servicio_id.fecha" => 'required|date',
                "servicios.$servicio_id.hora" => 'required|date_format:H:i',
                //profesional_id es requerido y debe existir en la tabla users
                "servicios.$servicio_id.profesional_id" => 'required|exists:users,id',
            ]);
        }

        // Guardar los turnos
        foreach ($serviciosSeleccionados as $servicio_id => $data) {
            Turno::create([
                'user_id'       => Auth::id(),
                'servicio_id'   => $servicio_id,
                'fecha'         => $data['fecha'],
                'hora'          => $data['hora'],
                'profesional_id'  => $data['profesional_id'],// Agregué profesional_id
                'metodo_pago'     => $request->input('metodo_pago'),
            ]);
        }

        return redirect()->route('cliente.mis-servicios')->with('success', 'Reserva realizada con éxito.');
    }


    public function historial()
    {
        $cliente = Auth::user();

        // Obtener los turnos realizados (pasados)
        $turnosRealizados = Turno::with('servicio')
            ->where('user_id', $cliente->id)
            ->where('fecha', '<', now()->toDateString()) // Filtra por fecha pasada
            ->orderByDesc('fecha')
            ->orderByDesc('hora')
            ->get();

        return view('clientes.historial', compact('turnosRealizados'));
    }


    public function misServicios()
    {
        $cliente = Auth::user();

        // Obtener los turnos futuros (pendientes)
        $turnosPendientes = Turno::with('servicio')
            ->where('user_id', $cliente->id)
            ->where('fecha', '>=', now()->toDateString()) // Filtra por fecha futura
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        return view('clientes.mis-servicios', compact('turnosPendientes'));
    }


    public function cancelar(Turno $turno)
    {
        if ($turno->user_id !== Auth::id()) {
            abort(403);
        }

        if ($turno->estado === 'pendiente') {
            $turno->delete(); // o marcarlo como cancelado
            return redirect()->route('cliente.mis-servicios')->with('success', 'Turno cancelado.');
        }

        return redirect()->route('cliente.mis-servicios')->with('error', 'No se puede cancelar este turno.');
    }


}