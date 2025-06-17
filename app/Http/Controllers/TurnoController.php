<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turno;
use App\Models\User;
use App\Models\Servicio;

class TurnoController extends Controller
{
    public function index(Request $request)
    {
        $turnos = Turno::with(['user', 'servicio'])
            ->orderBy('fecha')
            ->orderBy('hora')
            ->get();

        return view('admin.turnos.index', compact('turnos'));
    }

        public function edit($id)
    {
        $turno = Turno::with(['user', 'servicio'])->findOrFail($id);
        $servicios = Servicio::all();
        $profesionales = User::where('role', 'profesional')->orderBy('name')->get();

        return view('admin.turnos.edit', compact('turno', 'servicios', 'profesionales'));
    }
    public function update(Request $request, $id)
    {
        $turno = Turno::findOrFail($id);

        $request->validate([
            'hora' => 'required',
            'estado' => 'required',
            'servicio_id' => 'required|exists:servicios,id',
            'profesional_id' => 'nullable|exists:users,id',
        ]);

        $turno->hora = $request->hora;
        $turno->estado = $request->estado;
        $turno->servicio_id = $request->servicio_id;
        $turno->profesional_id = $request->profesional_id;
        $turno->save();

        // 👇 Esto es clave
        return redirect()->route('admin.turnos.index')->with('success', 'Turno actualizado correctamente.');
    }
    
    public function destroy($id)
    {
        $turno = Turno::findOrFail($id);
        $turno->estado = 'cancelado';
        $turno->save();

        return redirect()->back()->with('success', 'El turno fue cancelado correctamente.');
    }



}
