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
class ClienteTurnoController extends Controller
{
    public function create()
    {
        $servicios = Servicio::all();
        return view('clientes.reservar-turno', compact('servicios'));
    }

    public function store(Request $request)
{
    $request->validate([
        'servicios' => 'required|array|min:1',
        'servicios.*.fecha' => 'required|date|after_or_equal:' . now()->addDays(2)->format('Y-m-d'),
        'servicios.*.hora' => 'required|date_format:H:i',
        'metodo_pago' => 'required|in:debito',
    ]);

    $user = Auth::user();
    $turnos = [];

    foreach ($request->servicios as $servicioId => $datos) {
        $servicio = Servicio::findOrFail($servicioId);

        $fecha = $datos['fecha'];
        $hora = $datos['hora'];

        // Calcular descuento si aplica
        $fechaHora = new \DateTime("$fecha $hora");
        $ahora = new \DateTime();
        $horas = ($fechaHora->getTimestamp() - $ahora->getTimestamp()) / 3600;

        $descuento = $horas >= 48 ? 0.15 : 0;
        $precioFinal = $servicio->precio * (1 - $descuento);

        $turnos[] = Turno::create([
            'user_id' => $user->id,
            'servicio_id' => $servicio->id,
            'fecha' => $fecha,
            'hora' => $hora,
            'estado' => 'pendiente',
            'pagado' => true,
            'monto' => $precioFinal,
            'medio_pago' => 'debito',
            'notas' => 'Reserva desde sistema web',
        ]);
    }

    // (Opcional) Lógica para agrupar todos en un solo comprobante, si querés.

    return redirect()->route('cliente.historial')
        ->with('success', '¡Servicios reservados con éxito!');
}

    public function historial()
    {
        $turnos = Turno::with('servicio')
            ->where('user_id', Auth::id())
            ->orderByDesc('fecha')
            ->orderByDesc('hora')
            ->get();

        return view('clientes.historial', compact('turnos'));
    }


    public function misServicios()
    {
        $cliente = Auth::user();

        // Obtener todos los turnos del cliente con el servicio cargado
        $turnos = Turno::with('servicio')
                    ->where('user_id', $cliente->id)
                    ->orderBy('fecha', 'desc')
                    ->orderBy('hora', 'desc')
                    ->get();

        // Separar por estado
        $pendientes = $turnos->filter(function ($turno) {
            return Carbon::createFromFormat('Y-m-d H:i', $turno->fecha . ' ' . $turno->hora)->isFuture();
        });

        $realizados = $turnos->filter(function ($turno) {
            return Carbon::createFromFormat('Y-m-d H:i', $turno->fecha . ' ' . $turno->hora)->isPast();
        });

        return view('clientes.mis-servicios', compact('pendientes', 'realizados'));
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