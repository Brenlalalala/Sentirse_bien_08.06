<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ClientePagoController extends Controller
{
public function mostrarFormulario()
{
    $monto = 5000; // centavos (ej: $50.00 USD)
    return view('clientes.pagar', compact('monto'));
}


public function procesar(Request $request)
{
    Stripe::setApiKey(config('services.stripe.secret'));

    try {
        $charge = Charge::create([
            'amount' => $request->amount,
            'currency' => 'usd',
            'description' => 'Pago de servicio',
            'source' => $request->token,
        ]);

        // Enviar correo al cliente
        Mail::to(auth()->user()->email)->send(new PagoExitosoMail($charge));

        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}

}
