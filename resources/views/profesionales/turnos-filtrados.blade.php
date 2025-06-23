@extends('layouts.sidebar')

@section('content')
<div class="mt-8">
    <h2 class="text-2xl font-bold mb-6 text-pink-600">Mis Turnos</h2>

    <!-- Formulario -->
    <form method="GET" action="{{ route('profesional.turnos') }}" class="mb-8">
        <!-- Filtros en una línea -->
        <div class="flex flex-wrap gap-4">
            <div>
                <label class="block font-semibold mb-1">Fecha Inicio:</label>
                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="form-input w-full rounded border-gray-300">
            </div>
            <div>
                <label class="block font-semibold mb-1">Fecha Fin:</label>
                <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="form-input w-full rounded border-gray-300">
            </div>
            <div>
                <label class="block font-semibold mb-1">Servicio:</label>
                <select name="servicio_id" class="form-select w-full rounded border-gray-300">
                    <option value="">Todos</option>
                    @foreach($servicios as $servicio)
                        <option value="{{ $servicio->id }}" {{ request('servicio_id') == $servicio->id ? 'selected' : '' }}>
                            {{ $servicio->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Estado:</label>
                <select name="estado" class="form-select w-full rounded border-gray-300">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="confirmado" {{ request('estado') == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                    <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
        </div>

        <!-- Botones alineados a la derecha -->
        <div class="flex justify-end gap-4 mt-4">
            <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded shadow hover:bg-pink-700 transition">
                Filtrar
            </button>
            <a href="{{ route('profesional.turnos.pdf', request()->all()) }}" target="_blank"
               class="bg-gray-700 text-white px-4 py-2 rounded shadow hover:bg-gray-800 transition">
                Exportar PDF
            </a>
        </div>
    </form>

    <!-- Tabla -->
    @if ($turnos->isEmpty())
        <p class="text-lg text-gray-600 mt-6">No hay turnos para estos filtros.</p>
    @else
        @foreach ($turnos as $key => $listaTurnos)
            @php
                [$fecha, $servicio] = explode('|', $key);
            @endphp

            <h3 class="text-xl font-semibold mt-6 mb-2 text-pink-600">
                {{ ucfirst(\Carbon\Carbon::parse($fecha)->locale('es')->translatedFormat('l d \d\e F \d\e Y')) }}
                - {{ $servicio }}
            </h3>

            <p class="text-sm text-gray-500 mb-2 italic">
                *Hacé clic sobre el nombre del cliente para ver su historial.*
            </p>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded shadow mb-8 text-center">
                    <thead class="bg-pink-100 text-pink-700 text-sm uppercase">
                        <tr>
                            <th class="py-2 px-4">Hora</th>
                            <th class="py-2 px-4">Cliente</th>
                            <th class="py-2 px-4">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        @foreach ($listaTurnos as $turno)
                            <tr class="border-b hover:bg-pink-50">
                                <td class="py-2 px-4">{{ \Carbon\Carbon::parse($turno->hora)->format('H:i') }}</td>
                                <td class="py-2 px-4">
                                    @if($turno->user)
                                        <a href="{{ route('profesional.historial.ver', ['cliente_nombre' => $turno->user->name]) }}"
                                           class="text-pink-600 hover:underline">
                                            {{ $turno->user->name }}
                                        </a>
                                    @else
                                        Sin cliente
                                    @endif
                                </td>
                                <td class="py-2 px-4 capitalize">{{ $turno->estado }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @endif
</div>
@endsection
