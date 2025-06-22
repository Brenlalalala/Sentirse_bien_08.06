<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HorarioProfesional;
use App\Models\User;
use App\Models\Servicio;
use Spatie\Permission\Traits\HasRoles;

class HorarioProfesionalController extends Controller
{
    public function index()
    {
        $horarios = HorarioProfesional::with('profesional')->get();
        return view('admin.horarios.index', compact('horarios'));
    }

        public function create()
        {
            $profesionales = User::role('profesional')->get();
            return view('admin.horarios.create', compact('profesionales')); // Eliminado 'servicios'
        }

        public function edit(HorarioProfesional $horario)
        {
            $profesionales = User::role('profesional')->get();
            return view('admin.horarios.edit', compact('horario', 'profesionales')); // Eliminado 'servicios'
        }


    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'dia' => 'required|in:lunes,martes,miércoles,jueves,viernes,sábado,domingo',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ]);

        HorarioProfesional::create($request->except('servicio_id'));

        return redirect()->route('admin.horarios.index')->with('success', 'Horario creado correctamente.');
    }

    public function update(Request $request, HorarioProfesional $horario)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'dia' => 'required|in:lunes,martes,miércoles,jueves,viernes,sábado,domingo',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ]);

        
        $horario->update($request->except('servicio_id'));
        
        return redirect()->route('admin.horarios.index')->with('success', 'Horario actualizado correctamente.');
    }

    public function destroy(HorarioProfesional $horario)
    {
        $horario->delete();

        return redirect()->route('admin.horarios.index')->with('success', 'Horario eliminado correctamente.');
    }
}
