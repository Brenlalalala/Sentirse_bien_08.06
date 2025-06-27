<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Models\Turno;
use App\Models\Pago;
use App\Models\Servicio;
use Carbon\Carbon;
use App\Mail\PagoExitosoMail;
// use PDF; // Descomenta si usás Dompdf para PDF adjunto
use App\Mail\ComprobantePago;
use Illuminate\Support\Facades\Log;


class ClientePagoController extends Controller
{
    public function mostrarFormulario()
    {
        $monto = 5000; // centavos (ej: $50.00 USD)
        return view('clientes.pagar', compact('monto'));
    }

    // public function procesar(Request $request)
    // {
    //     Stripe::setApiKey(config('services.stripe.secret'));

    //     try {
    //         $charge = Charge::create([
    //             'amount' => $request->amount,
    //             'currency' => 'usd',
    //             'description' => 'Pago de servicio',
    //             'source' => $request->token,
    //         ]);

    //         // Enviar correo al cliente
    //         Mail::to(auth()->user()->email)->send(new PagoExitosoMail($charge));

    //         return response()->json(['success' => true]);

    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => $e->getMessage()]);
    //     }
    // }


    public function procesarPago(Request $request)
    {
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,debito,credito',
            'turnos' => 'required|array',
        ]);

        $user = Auth::user();
        $metodo = $request->input('metodo_pago');
        $turnos = $request->input('turnos');

        $montoTotal = 0;
        $descuento = 0;
        $turnosDetalle = [];

        foreach ($turnos as $turnoJson) {
            $turno = json_decode($turnoJson, true);
            $servicio = Servicio::find($turno['servicio_id']);
            $precio = $servicio?->precio ?? 0;

            $fecha = Carbon::parse($turno['fecha']);
            $hoy = Carbon::now();

            $precioFinal = $precio;

            if ($metodo === 'debito' && $hoy->diffInHours($fecha, false) >= 48) {
                $precioFinal *= 0.85;
                $descuento = 0.15;
            }

            $montoTotal += $precioFinal;

            $turnosDetalle[] = [
                'servicio' => $servicio->nombre ?? 'Servicio',
                'fecha' => $turno['fecha'],
                'hora' => $turno['hora'],
                'precio' => $precio,
                'precio_final' => $precioFinal,
            ];
        }

        $pago = Pago::create([
            'user_id' => $user->id,
            'monto' => $montoTotal,
            'descuento' => $descuento,
            'forma_pago' => $metodo,
            'fecha_pago' => now(),
        ]);

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

        // 🚨 Asegurate que la clase ComprobantePago extienda Mailable y tenga bien configurada la vista.
        try {
            Mail::to($user->email)->send(new PagoExitosoMail($pago, $turnos, $user->name));
        } catch (\Exception $e) {
            Log::error('Error al enviar el comprobante de pago: ' . $e->getMessage());
            return redirect()->route('cliente.mis-servicios')->with('warning', 'Turnos reservados, pero hubo un error al enviar el comprobante por correo.');
        }

        if (Session::has('grupo2')) {
            return redirect()->route('cliente.pagar.segundo-grupo')->with('success', 'Primer turno pagado. Ahora elige cómo abonar el siguiente.');
        }

        return redirect()->route('cliente.mis-servicios')->with('success', '¡Turnos reservados con éxito! Se ha enviado el comprobante a tu correo.');
    }

    public function mostrarSegundoGrupo()
    {
        if (!Session::has('grupo2')) {
            return redirect()->route('cliente.mis-servicios');
        }

        $grupo2 = Session::get('grupo2');

        return view('clientes.pago-segundo-grupo', compact('grupo2'));
    }

    public function procesarPagoSegundoGrupo(Request $request)
    {
        $request->validate([
            'metodo_pago' => 'required|in:efectivo,debito,credito',
            'turnos' => 'required|array',
        ]);

        $userId = Auth::id();
        $metodo = $request->input('metodo_pago');
        $turnos = $request->input('turnos');

        $montoTotal = 0;
        $descuento = 0;

        foreach ($turnos as $turnoJson) {
            $turno = json_decode($turnoJson, true);
            $servicio = Servicio::find($turno['servicio_id']);
            $precio = $servicio?->precio ?? 0;

            $fecha = Carbon::parse($turno['fecha']);
            $hoy = Carbon::now();

            $precioFinal = $precio;

            if ($metodo === 'debito' && $hoy->diffInHours($fecha, false) >= 48) {
                $precioFinal *= 0.85;
                $descuento = 0.15;
            }

            $montoTotal += $precioFinal;
        }

        $pago = Pago::create([
            'user_id' => $userId,
            'monto' => $montoTotal,
            'descuento' => $descuento,
            'forma_pago' => $metodo,
            'fecha_pago' => now(),
        ]);

        $turnosCreados = [];

        foreach ($turnos as $turnoJson) {
            $turno = json_decode($turnoJson, true);

            $nuevoTurno = Turno::create([
                'user_id' => $userId,
                'servicio_id' => $turno['servicio_id'],
                'profesional_id' => $turno['profesional_id'],
                'fecha' => $turno['fecha'],
                'hora' => $turno['hora'],
                'metodo_pago' => $metodo,
                'pago_id' => $pago->id,
            ]);

            $turnosCreados[] = $nuevoTurno;
        }

        // Cargar relaciones para el mail
        $turnosConRelacion = collect($turnosCreados)->load(['servicio', 'profesional']);

        // Opcional: generar PDF y adjuntar
        // $pdf = PDF::loadView('pdf.comprobante_pago', [
        //     'pago' => $pago,
        //     'turnos' => $turnosConRelacion,
        // ]);

        // Enviar mail al usuario
        $user = Auth::user();

        try {
            Mail::to($user->email)
                ->send(new PagoExitosoMail($pago, $turnosConRelacion, $user->name));
        } catch (\Exception $e) {
            logger()->error("Error al enviar el mail: " . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al enviar el mail. Por favor, contactá al administrador.');
        }



        Session::forget(['grupo1', 'grupo2', 'metodo_pago']);

        return redirect()->route('cliente.mis-servicios')->with('success', '¡Todos los turnos fueron reservados y pagados! 💖');
    }
}
