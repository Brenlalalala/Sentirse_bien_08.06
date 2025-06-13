<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Servicio;
use App\Models\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ComprobantePagoMail;
use Carbon\Carbon;


class ClienteTurnoController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validación
        $request->validate([
            'servicios' => 'required|array|min:1',
            'fecha' => 'required|date|after_or_equal:' . Carbon::now()->addDays(2)->format('Y-m-d'),
            'hora' => 'required',
            'forma_pago' => 'required|in:debito,otro',
        ]);

        $user = Auth::user();

        // 2. Preparación de fecha y hora
        $fecha = Carbon::parse($request->fecha);
        $hora = Carbon::parse($request->hora);
        $fechaHoraTurno = $fecha->copy()->setTimeFrom($hora);

        // 3. Obtener servicios y calcular subtotal
        $servicios = Servicio::whereIn('id', $request->servicios)->get();
        $subtotal = $servicios->sum('precio');

        // 4. Calcular descuento si corresponde (48hs de anticipación y pago con débito)
        $ahora = Carbon::now();
        $horasAnticipacion = $ahora->diffInHours($fechaHoraTurno, false);
        $aplicaDescuento = $horasAnticipacion >= 48 && $request->forma_pago === 'debito';

        $descuento = $aplicaDescuento ? $subtotal * 0.15 : 0;
        $total = $subtotal - $descuento;

        // 5. Registrar el pago
        $pago = Pago::create([
            'user_id' => $user->id,
            'monto' => $total,
            'descuento' => $descuento,
            'forma_pago' => $request->forma_pago,
            'fecha_pago' => now(),
        ]);

        // 6. Crear turnos asociados al pago
        foreach ($servicios as $servicio) {
            Turno::create([
                'cliente_id' => $user->id,
                'servicio_id' => $servicio->id,
                'fecha' => $request->fecha,
                'hora' => $request->hora,
                'estado' => 'pendiente',
                'pago_id' => $pago->id, // asegúrate que el campo exista en la tabla `turnos`
            ]);
        }

        // 7. Enviar comprobante por correo
        Mail::to($user->email)->send(new ComprobantePagoMail($pago));

        // 8. Redirigir con mensaje
        return redirect()->route('cliente.servicios.index')->with('success', '¡Turno reservado y comprobante enviado!');
    }
}
