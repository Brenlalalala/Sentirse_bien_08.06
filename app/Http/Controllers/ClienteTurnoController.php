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
        // Carga los servicios con sus profesionales vinculados profesional_servicio
        $servicios = Servicio::with('profesionales')->get();
 
        return view('clientes.reservar-turno', compact('servicios'));
    }

    public function store(Request $request)
    {
        $serviciosSeleccionados = [];

        foreach ($request->input('servicios', []) as $servicio_id => $data) {
            if (isset($data['seleccionado']) && $data['seleccionado']) {
                $serviciosSeleccionados[$servicio_id] = $data;
            }
        }

        if (empty($serviciosSeleccionados)) {
            return redirect()->back()->withErrors(['Debe seleccionar al menos un servicio.'])->withInput();
        }

        foreach ($serviciosSeleccionados as $servicio_id => $data) {
            // Validaciones por servicio
            $request->validate([
                "servicios.$servicio_id.fecha" => 'required|date',
                "servicios.$servicio_id.hora" => 'required|date_format:H:i',
                "servicios.$servicio_id.profesional_id" => 'required|exists:users,id',
            ]);

            $profesional = User::find($data['profesional_id']);
            $servicio = Servicio::find($servicio_id);

            if (!$servicio->profesionales->contains($profesional)) {
                return redirect()->back()->withErrors(["El profesional seleccionado no está asignado a este servicio."])->withInput();
            }

            $disponibilidad = $this->esHorarioDisponible($data['profesional_id'], $servicio_id, $data['fecha'], $data['hora'], Auth::id());

            if ($disponibilidad !== true) {
                return redirect()->back()->withErrors([$disponibilidad])->withInput();
            }

        }

        // Guardar los turnos
        foreach ($serviciosSeleccionados as $servicio_id => $data) {
            Turno::create([
                'user_id'       => Auth::id(),
                'servicio_id'   => $servicio_id,
                'profesional_id'=> $data['profesional_id'],
                'fecha'         => $data['fecha'],
                'hora'          => $data['hora'],
                'metodo_pago'   => $request->input('metodo_pago'),
            ]);
        }
            $metodo = $request->input('metodo_pago');

            if (in_array($metodo, ['debito', 'credito'])) {
                return redirect()->route('pago.formulario')->with('success', 'Turno reservado. Ahora realizá el pago.');
            }

            return redirect()->route('cliente.mis-servicios')->with('success', 'Turno reservado. El pago se encuentra pendiente y debe abonarse en el local.');


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

    private function esHorarioDisponible($profesional_id, $servicio_id, $fecha, $hora, $cliente_id)
{
    // Verifica si el horario ya está reservado por el profesional
    $ocupadoProfesional = Turno::where('profesional_id', $profesional_id)
        ->where('fecha', $fecha)
        ->where('hora', $hora)
        ->where('estado', '!=', 'cancelado')
        ->exists();

    if ($ocupadoProfesional) {
        return 'El profesional ya tiene un turno reservado en ese horario.';
    }

    // Verifica si el cliente ya tiene un turno en ese horario
    $ocupadoCliente = Turno::where('user_id', $cliente_id)
        ->where('fecha', $fecha)
        ->where('hora', $hora)
        ->where('estado', '!=', 'cancelado')
        ->exists();

    if ($ocupadoCliente) {
        return 'Ya tenés un turno reservado en ese horario.';
    }

    return true;
}

}