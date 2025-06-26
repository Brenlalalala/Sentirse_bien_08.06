<?php

namespace App\Http\Controllers;


use App\Models\Servicio;
use App\Models\Turno;
use App\Models\Pago;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ComprobantePago;
use App\Http\Controllers\TurnoController;
use App\Models\User;
use Illuminate\Support\Facades\Session;



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
    $user = Auth::user();
    $servicios = $request->input('servicios', []);
    $pagos = $request->input('pagos', []);
    $metodoUnico = $request->input('metodo_pago');

    $agrupadosPorFecha = [];

    foreach ($servicios as $id => $s) {
    if (!isset($s['seleccionado'])) continue;

    if (empty($s['fecha']) || empty($s['hora']) || empty($s['profesional_id'])) {
        return back()->withErrors(['Completá todos los campos de los servicios seleccionados.']);
    }

    $servicio = Servicio::find($id);
    $profesional = User::find($s['profesional_id']);
    $fecha = $s['fecha'];

    $agrupadosPorFecha[$fecha][] = [
        'user_id' => $user->id,
        'servicio_id' => $id,
        'servicio_nombre' => $servicio?->nombre ?? 'Servicio desconocido',
        'precio' => $servicio?->precio ?? 0,

        'profesional_id' => $s['profesional_id'],
        'profesional_nombre' => $profesional?->name ?? 'Profesional desconocido',
        'fecha' => $s['fecha'],
        'hora' => $s['hora'],
        'estado' => 'pendiente'
    ];
}




    if (count($agrupadosPorFecha) === 1) {
        // Pago único
        $fecha = array_key_first($agrupadosPorFecha);
        $grupo = $agrupadosPorFecha[$fecha];

        if ($metodoUnico === 'efectivo') {
            foreach ($grupo as $turno) Turno::create($turno);
            return redirect()->route('cliente.mis-servicios')->with('success', 'Turnos reservados. Debe abonar en el spa.');
        }

        // Débito o crédito: guardar en sesión
        Session::put('pagos_tarjeta', [$grupo]);
        return redirect()->route('cliente.pago.tarjeta');
    }

    // Múltiples fechas → se usa el array 'pagos'
    $grupos = [];
    foreach ($agrupadosPorFecha as $fecha => $turnos) {
         $metodo = $pagos[$fecha] ?? null;
        // if (!$metodo) return back()->withErrors(["Falta el método de pago para la fecha $fecha."]);

        if ($metodo === 'efectivo') {
            foreach ($turnos as $turno) Turno::create($turno);
        } else {
            $grupos[] = $turnos;
        }
    }

    if (count($grupos) > 0) {
        Session::put('pagos_tarjeta', $grupos);
        return redirect()->route('cliente.pago.tarjeta');
    }

    return view('cliente.mis-servicios')->with('success', 'Turnos reservados. Debe abonar en el spa.');
}

public function vistaPagoTarjeta()
{
    $grupos = Session::get('pagos_tarjeta');

    if (!$grupos || empty($grupos)) {
        return redirect()->route('cliente.turnos.create')->withErrors(['No hay pagos pendientes.']);
    }

    return view('clientes.pago-tarjeta-simulado', compact('grupos'));
}

public function procesarPagoTarjeta(Request $request)
{
    $turnosPorFecha = $request->input('turnos', []);
    $metodos = $request->input('pagos', []);
    $userId = Auth::id();
    $hoy = \Carbon\Carbon::now();

    foreach ($turnosPorFecha as $fecha => $turnos) {
        $metodo = $metodos[$fecha] ?? null;
        if (!$metodo) continue;

        $montoTotal = 0;
        $descuentoTotal = 0;

        // Primero calculamos el monto total y descuento por el grupo de turnos de esta fecha
        foreach ($turnos as $turnoJson) {
            $turno = is_array($turnoJson) ? $turnoJson : json_decode($turnoJson, true);
            $servicio = \App\Models\Servicio::find($turno['servicio_id']);
            $precio = $servicio?->precio ?? 0;

            $fechaTurno = \Carbon\Carbon::parse($turno['fecha']);
            $precioFinal = $precio;

            // Aplica 15% descuento si paga con débito y reserva con 48+ horas de anticipación
            if ($metodo === 'debito' && $hoy->diffInHours($fechaTurno, false) >= 48) {
                $precioFinal *= 0.85;
                $descuentoTotal += $precio * 0.15;
            }

            $montoTotal += $precioFinal;
        }

        // Creamos el pago una sola vez por grupo (fecha)
        $pago = \App\Models\Pago::create([
            'user_id' => $userId,
            'monto' => $montoTotal,
            'descuento' => $descuentoTotal,
            'forma_pago' => $metodo,
            'fecha_pago' => now(),
        ]);

        // Creamos cada turno asociándolo al pago creado
        foreach ($turnos as $turnoJson) {
            $turno = is_array($turnoJson) ? $turnoJson : json_decode($turnoJson, true);

            \App\Models\Turno::create([
                'user_id' => $userId,
                'servicio_id' => $turno['servicio_id'],
                'profesional_id' => $turno['profesional_id'],
                'fecha' => $turno['fecha'],
                'hora' => $turno['hora'],
                'estado' => 'pendiente',
                'metodo_pago' => $metodo,
                'pago_id' => $pago->id,
            ]);
        }
    }

    return redirect()->route('cliente.mis-servicios')->with('success', 'Turnos reservados y pagos procesados.');
}



//     public function confirmarGrupo1(Request $request)
//         {
//             $grupo1 = Session::get('grupo1');
//             $metodo = Session::get('metodo_pago');

//             if (!$grupo1 || !$metodo) {
//                 return redirect()->route('cliente.mis-servicios')->with('error', 'Sesión expirada. Volvé a seleccionar tus turnos.');
//             }

//             // Serializamos los turnos para pasarlos al formulario de pago
//             $turnosJson = array_map(function ($t) {
//                 return json_encode($t);
//             }, $grupo1);

//             return view('clientes.pagar', [
//                 'turnos' => $turnosJson,
//                 'metodo' => $metodo,
//                 'fechaGrupo' => $grupo1[0]['fecha'] ?? null,
//             ]);
//         }


//    public function pagarSegundoGrupo()
//         {
//             $grupo2 = Session::get('grupo2');
//             $metodo = Session::get('metodo_pago');

//             if (!$grupo2 || !$metodo) {
//                 return redirect()->route('cliente.mis-servicios')->with('error', 'Sesión expirada.');
//             }

//             $turnosJson = array_map(function ($t) {
//                 return json_encode($t);
//             }, $grupo2);

//             return view('clientes.pago-segundo-grupo', [
//                 'turnos' => $turnosJson,
//                 'metodo' => $metodo,
//                 'fechaGrupo' => $grupo2[0]['fecha'] ?? null,
//             ]);
//         }


    

    private function esHorarioDisponible($profesional_id, $servicio_id, $fecha, $hora, $cliente_id)
    {
        $ocupadoProfesional = Turno::where('profesional_id', $profesional_id)
            ->where('fecha', $fecha)
            ->where('hora', $hora)
            ->where('estado', '!=', 'cancelado')
            ->exists();

        if ($ocupadoProfesional) {
            return 'El profesional ya tiene un turno reservado en ese horario.';
        }

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

