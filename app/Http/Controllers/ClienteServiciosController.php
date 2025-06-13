<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use App\Models\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\ComprobantePago;
use App\Http\Controllers\ClienteTurnoController;

class ClienteServiciosController extends Controller
{
    public function seleccionar()
    {
        $servicios = Servicio::all();
        return view('cliente.seleccionar', compact('servicios'));
    }

    public function guardarSeleccion(Request $request)
    {
        $request->validate([
            'servicios.*.fecha' => 'required_with:servicios.*.seleccionado|date|after_or_equal:today',
            'servicios.*.hora' => 'required_with:servicios.*.seleccionado|date_format:H:i',
            'metodo_pago' => 'required|in:debito', // solo débito permitido
        ], [
            'metodo_pago.required' => 'Debes seleccionar un método de pago.',
            'metodo_pago.in' => 'Solo se permite el pago con tarjeta de débito.',
        ]);

        $serviciosSeleccionados = $request->input('servicios', []);
        $cliente = Auth::user();

        $reservas = [];
        $fechas = [];

        foreach ($serviciosSeleccionados as $servicioId => $datos) {
            if (isset($datos['seleccionado'])) {
                $fecha = $datos['fecha'];
                $hora = $datos['hora'];
                $fechas[] = $fecha;

                // Obtener el servicio
                $servicio = Servicio::findOrFail($servicioId);
                $precioOriginal = $servicio->precio;

                // Verificar si aplica descuento
                $fechaServicio = new \DateTime($fecha . ' ' . $hora);
                $ahora = new \DateTime();
                $intervalo = $ahora->diff($fechaServicio);
                $horasHastaServicio = ($intervalo->days * 24) + $intervalo->h;

                $descuento = 0;
                if ($horasHastaServicio >= 48) {
                    $descuento = 0.15;
                }

                $precioFinal = $precioOriginal * (1 - $descuento);

                // Crear turno
                $turno = ClienteTurnoController::create([
                    'user_id' => $cliente->id,
                    'servicio_id' => $servicioId,
                    'fecha' => $fecha,
                    'hora' => $hora,
                    'precio' => $precioFinal,
                ]);

                $turnos[] = $turno;
            }
        }

        // Verificar si todas las fechas son iguales para permitir un único pago
        $pagoAgrupado = count(array_unique($fechas)) === 1;

        // Enviar comprobante por email
        Mail::to($cliente->email)->send(new ComprobantePago($turnos, $pagoAgrupado));

        return redirect()->route('cliente.servicios.seleccionar')
            ->with('success', '✅ Reserva realizada con éxito. Recibirás un comprobante por email.');
    }
}
