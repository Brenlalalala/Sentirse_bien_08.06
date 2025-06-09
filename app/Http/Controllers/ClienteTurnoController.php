<?php

namespace App\Http\Controllers;


use App\Models\Servicio;
use App\Models\Turno;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            'fecha' => 'required|date|after_or_equal:' . Carbon::now()->addDays(2)->format('Y-m-d'),
            'hora' => 'required'
        ]);

        foreach ($request->servicios as $servicio_id) {
            Turno::create([
                'cliente_id' => Auth::id(),
                'servicio_id' => $servicio_id,
                'fecha' => $request->fecha,
                'hora' => $request->hora,
                'estado' => 'pendiente'
            ]);
        }

        return redirect()->route('cliente.reservar-turno')->with('success', '¡Turno reservado con éxito!');

    }
}
