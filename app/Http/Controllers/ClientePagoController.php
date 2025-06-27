<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Turno;
use App\Models\Pago;
use App\Models\Servicio;
use Carbon\Carbon;
use App\Mail\PagoExitosoMail;
use Barryvdh\DomPDF\Facade\Pdf;


class ClientePagoController extends Controller
{
    public function mostrarFormulario()
    {
        $monto = 5000; // centavos (ej: $50.00 USD)
        return view('clientes.pagar', compact('monto'));
    }

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
                'profesional' => 'Profesional', // Opcional: si querés agregarlo
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

        // Generar y guardar el comprobante PDF
        $pdf = Pdf::loadView('pdf.comprobante_pago', [
            'pago' => $pago,
            'turnos' => $turnosDetalle,
            'cliente' => $user,
        ]);

        $nombreArchivo = 'comprobante_' . $pago->id . '.pdf';
        Storage::put('comprobantes/' . $nombreArchivo, $pdf->output());

        $pago->comprobante = $nombreArchivo;
        $pago->save();

        try {
            Mail::to($user->email)->send(new PagoExitosoMail($pago, $turnosDetalle, $user->name));
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
                'profesional' => 'Profesional',
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

        $turnosCreados = [];

        foreach ($turnos as $turnoJson) {
            $turno = json_decode($turnoJson, true);

            $nuevoTurno = Turno::create([
                'user_id' => $user->id,
                'servicio_id' => $turno['servicio_id'],
                'profesional_id' => $turno['profesional_id'],
                'fecha' => $turno['fecha'],
                'hora' => $turno['hora'],
                'metodo_pago' => $metodo,
                'pago_id' => $pago->id,
            ]);

            $turnosCreados[] = $nuevoTurno;
        }

        // Guardar comprobante PDF
        $pdf = Pdf::loadView('pdf.comprobante_pago', [
            'pago' => $pago,
            'turnos' => $turnosDetalle,
            'cliente' => $user,
        ]);

        $nombreArchivo = 'comprobante_' . $pago->id . '.pdf';
        Storage::put('comprobantes/' . $nombreArchivo, $pdf->output());

        $pago->comprobante = $nombreArchivo;
        $pago->save();

        try {
            Mail::to($user->email)
                ->send(new PagoExitosoMail($pago, $turnosDetalle, $user->name));
        } catch (\Exception $e) {
            Log::error("Error al enviar el mail: " . $e->getMessage());
            return back()->with('error', 'Ocurrió un error al enviar el mail. Por favor, contactá al administrador.');
        }

        Session::forget(['grupo1', 'grupo2', 'metodo_pago']);

        return redirect()->route('cliente.mis-servicios')->with('success', '¡Todos los turnos fueron reservados y pagados! 💖');
    }

public function generarComprobante($turnoId)
{
    // Encuentra el turno, incluyendo el pago relacionado
    $turno = Turno::with('pago', 'servicio', 'profesional')->findOrFail($turnoId);

    // Verifica si hay un pago asociado
    if (!$turno->pago) {
        return redirect()->back()->with('error', 'No hay pago asociado a este turno.');
    }

    // Datos que se enviarán al PDF
    $data = [
        'turno' => $turno,
        'pago' => $turno->pago,
        'profesional' => $turno->profesional,
        'turnos' => [$turno]  // Asegúrate de pasar el turno aquí
    ];

    // Cargar la vista para el comprobante (en tu caso, pdf/comprobante_pago)
    $pdf = PDF::loadView('pdf.comprobante_pago', $data);

    // Descargar el PDF
    return $pdf->download('comprobante_turno_' . $turno->id . '.pdf');
}


}
