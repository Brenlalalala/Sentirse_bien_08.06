@extends('layouts.sidebar')

@section('content')
<div class="mt-8">
    <h2 class="text-2xl font-bold mb-6 text-pink-600">Turnos por día</h2>

    <!-- Formulario de filtros -->
    <form method="GET" action="{{ route('admin.turnos.dia') }}" class="mb-8">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div>
                <label class="block font-semibold mb-1">Fecha Inicio:</label>
                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio', now()->toDateString()) }}" class="form-input w-full rounded border-gray-300 shadow-sm focus:ring-pink-500 focus:border-pink-500">
            </div>
            <div>
                <label class="block font-semibold mb-1">Fecha Fin:</label>
                <input type="date" name="fecha_fin" value="{{ request('fecha_fin', now()->toDateString()) }}" class="form-input w-full rounded border-gray-300 shadow-sm focus:ring-pink-500 focus:border-pink-500">
            </div>
            <div>
                <label class="block font-semibold mb-1">Servicio:</label>
                <select name="servicio_id" class="form-select w-full rounded border-gray-300 shadow-sm focus:ring-pink-500 focus:border-pink-500">
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
                <select name="estado" class="form-select w-full rounded border-gray-300 shadow-sm focus:ring-pink-500 focus:border-pink-500">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="confirmado" {{ request('estado') == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
                    <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Profesional:</label>
                <select name="profesional_id" class="form-select w-full rounded border-gray-300 shadow-sm focus:ring-pink-500 focus:border-pink-500">
                    <option value="">Todos</option>
                    @foreach($profesionales as $profesional)
                        <option value="{{ $profesional->id }}" {{ request('profesional_id') == $profesional->id ? 'selected' : '' }}>
                            {{ $profesional->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold mb-1">Cliente:</label>
                <input type="text" name="cliente_nombre" value="{{ request('cliente_nombre') }}" placeholder="Nombre del cliente" class="form-input w-full rounded border-gray-300 shadow-sm focus:ring-pink-500 focus:border-pink-500">
            </div>
        </div>

        <!-- Botones: Filtrar e Imprimir PDF -->
        <div class="flex justify-end gap-4 mt-4">
            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-2 rounded shadow transition">
                Filtrar
            </button>

            <a href="{{ route('admin.turnos.imprimir', request()->all()) }}" target="_blank"
               class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded shadow transition">
                Imprimir PDF
            </a>
        </div>
    </form>

    <!-- Resultados -->
    @if ($turnos->isEmpty())
        <p class="text-lg text-gray-600 mt-6">No hay turnos para estos filtros.</p>
    @else
        @foreach ($turnos as $key => $listaTurnos)
            @php
                [$fecha, $servicio] = explode('|', $key);
            @endphp

            <h3 class="text-2xl font-semibold mt-6 mb-2 text-pink-600">
                {{ ucfirst(\Carbon\Carbon::parse($fecha)->locale('es')->translatedFormat('l d \d\e F \d\e Y')) }}
            </h3>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded shadow mb-8">
                    <thead class="bg-pink-100 text-pink-700 uppercase text-sm leading-normal">
                        <tr>
                            <th class="py-3 px-6 text-left">Hora</th>
                            <th class="py-3 px-6 text-left">Cliente</th>
                            <th class="py-3 px-6 text-left">Servicio</th>
                            <th class="py-3 px-6 text-left">Profesional</th>
                            <th class="py-3 px-6 text-left">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 text-sm">
                        @foreach ($listaTurnos as $turno)
                            <tr class="border-b border-gray-200 hover:bg-pink-50 transition">
                                <td class="py-3 px-6 whitespace-nowrap">{{ \Carbon\Carbon::parse($turno->hora)->format('H:i') }}</td>
                                <td class="py-3 px-6 whitespace-nowrap">{{ $turno->user->name ?? 'Desconocido' }}</td>
                                <td class="py-3 px-6 whitespace-nowrap">{{ $turno->servicio->nombre ?? 'Sin servicio' }}</td>
                                <td class="py-3 px-6 whitespace-nowrap">{{ $turno->profesional->name ?? 'No asignado' }}</td>
                                <td class="py-3 px-6 whitespace-nowrap capitalize">{{ $turno->estado }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @endif
</div>
@endsection
