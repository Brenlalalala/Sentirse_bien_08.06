<?php

namespace App\Http\Controllers;

use App\Mail\PagoExitosoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\Pago;
use App\Models\Servicio;
use App\Models\Turno;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ClientePagoController extends Controller
{
    public function procesarPago(Request $request)
    {
        // Validación de entrada
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,debito,credito',
            'turnos' => 'required|array',
        ]);

        // Obtener el usuario autenticado
        $user = Auth::user();
        $metodo = $request->input('metodo_pago');
        $turnos = $request->input('turnos');

        // Inicializar variables
        $montoTotal = 0;
        $descuento = 0;
        $turnosDetalle = [];

        // Procesar cada turno
        foreach ($turnos as $turnoJson) {
            $turno = json_decode($turnoJson, true);
            $servicio = Servicio::find($turno['servicio_id']);
            $precio = $servicio?->precio ?? 0;

            $fecha = Carbon::parse($turno['fecha']);
            $hoy = Carbon::now();

            $precioFinal = $precio;

            // Aplicar descuento si el pago es con débito y la diferencia con la fecha es mayor a 48 horas
            if ($metodo === 'debito' && $hoy->diffInHours($fecha, false) >= 48) {
                $precioFinal *= 0.85;
                $descuento = 0.15;
            }

            // Acumular el monto total
            $montoTotal += $precioFinal;

            // Detalles de los turnos
            $turnosDetalle[] = [
                'servicio' => $servicio->nombre ?? 'Servicio',
                'fecha' => $turno['fecha'],
                'hora' => $turno['hora'],
                'precio' => $precio,
                'precio_final' => $precioFinal,
            ];
        }

        // Crear el registro de pago
        $pago = Pago::create([
            'user_id' => $user->id,
            'monto' => $montoTotal,
            'descuento' => $descuento,
            'forma_pago' => $metodo,
            'fecha_pago' => now(),
        ]);

        // Crear los turnos en la base de datos
        foreach ($turnos as $turnoJson) {
            $turno = json_decode($turnoJson, true);

            Turno::create([
                'user_id' => $user->id,
                'servicio_id' => $turno['servicio_id'],
                'profesional_id' => $turno['profesional_id'],
                'fecha' => $turno['fecha'],
                'hora' => $turno['hora'],
                'metodo_pago' => $metodo,
                'pago_id' => $pago->id,
            ]);
        }

        // Cargar los turnos recién creados
        $turnosCreados = Turno::where('pago_id', $pago->id)->with(['servicio', 'profesional'])->get();

        // Generar el PDF con los datos del comprobante de pago
        $pdf = PDF::loadView('pdf.comprobante_pago', [
            'pago' => $pago,
            'turnos' => $turnosCreados,
            'nombre' => $user->name,
        ])->output();

        try {
            // Enviar el correo con los datos y el PDF adjunto
            Mail::to($user->email)->send(new PagoExitosoMail(
                $user->name,  // nombre_cliente
                $turnosDetalle[0]['servicio'] ?? null,  // servicio
                $turnosCreados[0]->profesional->nombre ?? null,  // profesional
                $pago->fecha_pago->format('d/m/Y') ?? null,  // fecha_pago
                $turnosCreados[0]->fecha . ' a las ' . $turnosCreados[0]->hora ?? null,  // turno
                $pago->monto ?? null,  // monto
                $pdf  // pdf
            ));

        } catch (\Exception $e) {
            // Loguear cualquier error
            Log::error('Error al enviar el comprobante de pago: ' . $e->getMessage());
            return redirect()->route('cliente.mis-servicios')->with('warning', 'Turnos reservados, pero hubo un error al enviar el comprobante por correo.');
        }

        // Redirigir con mensaje de éxito
        return redirect()->route('cliente.mis-servicios')->with('success', '¡Turnos reservados con éxito! Se ha enviado el comprobante a tu correo.');
    }
}
