<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turno;
use App\Models\User;
use App\Models\Servicio;
use App\Models\HorarioProfesional;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


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
        $profesionales = User::role('profesional')->orderBy('name')->get();

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

        return redirect()->route('admin.turnos.index')->with('success', 'Turno actualizado correctamente.');
    }
    
    public function destroy($id)
    {
        $turno = Turno::findOrFail($id);
        $turno->estado = 'cancelado';
        $turno->save();

        return redirect()->back()->with('success', 'El turno fue cancelado correctamente.');
    }

    public function verHistorialCliente(User $user)
    {
        // Obtener los turnos realizados por el cliente
        $turnosRealizados = Turno::where('user_id', $user->id)
            ->where('estado', 'realizado')
            ->with('servicio')
            ->orderByDesc('fecha')
            ->get();

        // Retornar la vista con los turnos
        return view('admin.historial-cliente', compact('user', 'turnosRealizados'));
    }

    public function horariosDisponibles(Request $request)
    {
        try {
            $request->validate([
                'profesional_id' => 'required|exists:users,id',
                'servicio_id'    => 'required|exists:servicios,id',
                'fecha'          => 'required|date',
            ]);

            $profesional = User::findOrFail($request->profesional_id);
            $servicio = Servicio::findOrFail($request->servicio_id);
            $fecha = Carbon::parse($request->fecha);
            $diaNombre = strtolower($fecha->locale('es')->dayName);

            $horariosDia = $profesional->horarios()
                ->where('dia', $diaNombre)
                ->get();

            if ($horariosDia->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'El profesional no tiene horarios configurados para este día.']);
            }

            $duracion = $servicio->duracion;
            if (!$duracion) {
                return response()->json(['success' => false, 'message' => 'El servicio no tiene duración definida.']);
            }

            $slots = [];

            foreach ($horariosDia as $hp) {
                if (!$hp->hora_inicio || !$hp->hora_fin) {
                    Log::warning('Horario con hora_inicio o hora_fin nulos para profesional ID ' . $hp->user_id . ' en día ' . $hp->dia);
                    continue;
                }

                $inicio = Carbon::createFromTimeString($hp->hora_inicio);
                $fin = Carbon::createFromTimeString($hp->hora_fin);

                if ($inicio->gte($fin)) continue;

                $actual = $inicio->copy();

                while ($actual->copy()->addMinutes($duracion)->lte($fin)) {
                    $hora = $actual->format('H:i');
                    $ocupado = Turno::where('profesional_id', $profesional->id)
                        ->where('fecha', $fecha->toDateString())
                        ->where('hora', $hora)
                        ->where('estado', '!=', 'cancelado')
                        ->exists();

                    if (!$ocupado) {
                        $slots[] = [
                            'hora' => $hora,
                            'formatted' => $actual->format('h:i A'),
                        ];
                    }

                    $actual->addMinutes($duracion);
                }
            }

            return response()->json(['success' => true, 'horarios' => $slots]);

        } catch (\Throwable $e) {
            Log::error('Error en horariosDisponibles: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error interno'], 500);
        }
    }



}
