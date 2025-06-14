<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
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
        // Validación
        $request->validate([
            'servicios.*.fecha' => 'required_with:servicios.*.seleccionado|date|after_or_equal:today',
            'servicios.*.hora' => 'required_with:servicios.*.seleccionado|date_format:H:i',
            'metodo_pago' => 'required|in:debito', // Solo débito permitido
        ], [
            'metodo_pago.required' => 'Debes seleccionar un método de pago.',
            'metodo_pago.in' => 'Solo se permite el pago con tarjeta de débito.',
        ]);

        $serviciosSeleccionados = $request->input('servicios', []);
        $cliente = Auth::user();

        $reservas = [];
        $fechas = [];

        // Instanciar el ClienteTurnoController
        $turnoController = new ClienteTurnoController();

        // Crear turnos
        foreach ($serviciosSeleccionados as $servicioId => $datos) {
            if (isset($datos['seleccionado'])) {
                $fecha = $datos['fecha'];
                $hora = $datos['hora'];
                $fechas[] = $fecha;

                // Crear el turno utilizando el método del controlador ClienteTurnoController
                $turno = $turnoController->crearTurno($cliente->id, $servicioId, $fecha, $hora);

                $reservas[] = $turno;
            }
        }

        // Verificar si todas las fechas son iguales para permitir un único pago
        $pagoAgrupado = count(array_unique($fechas)) === 1;

        // Enviar comprobante por email
        Mail::to($cliente->email)->send(new ComprobantePago($reservas, $pagoAgrupado));

        return redirect()->route('cliente.servicios.seleccionar')
            ->with('success', '✅ Reserva realizada con éxito. Recibirás un comprobante por email.');
    }
}
