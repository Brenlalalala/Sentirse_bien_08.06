@extends('layouts.sidebar')

@section('content')
<div class="max-w-6xl mx-auto p-6 shadow rounded">
    <h2 class="text-2xl font-bold text-pink-600 mb-6">Gestión de Turnos</h2>

    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">
            <p class="font-bold">¡Éxito!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="overflow-x-auto">
         <table class="min-w-full bg-white rounded shadow mb-8">
            <thead class="bg-pink-100 text-pink-700 uppercase text-sm leading-normal">
                <th class="py-3 px-6 text-left">Servicio</th>
                <th class="py-3 px-6 text-left">Cliente</th>
                <th class="py-3 px-6 text-left">Fecha</th>
                <th class="py-3 px-6 text-left">Hora</th>
                <th class="py-3 px-6 text-left">Estado</th>
                <th class="py-3 px-6 text-left">Profesional</th>
                <th class="py-3 px-6 text-left">Acciones</th>
            </tr>
        </thead>
        <tbody class="text-gray-700 text-sm">
            @foreach($turnos as $turno)
            <tr class="border-b border-gray-200 hover:bg-pink-50 transition">
                <td class="py-2 px-4 whitespace-nowrap">{{ $turno->servicio->nombre ?? 'N/A' }}</td>
                <td class="py-2 px-4 whitespace-nowrap">{{ $turno->user->name ?? 'Desconocido' }}</td>
                <td class="py-2 px-4 whitespace-nowrap">
                    {{ ucfirst(\Carbon\Carbon::parse($turno->fecha)->locale('es')->translatedFormat('l d \d\e F \d\e Y')) }}
                </td>
                <td class="py-2 px-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($turno->hora)->format('H:i') }}</td>
                <td class="py-2 px-4 whitespace-nowrap">{{ ucfirst($turno->estado) }}</td>
                <td class="py-2 px-4 whitespace-nowrap">{{ $turno->profesional->name ?? 'No asignado' }}</td>
                <td class="py-2 px-4 whitespace-nowrap">
                    <a href="{{ route('admin.turnos.edit', $turno->id) }}" class="text-blue-600 hover:underline">Editar</a>

                    <form action="{{ route('admin.turnos.cancelar', $turno->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-2"
                            onclick="return confirm('¿Estás seguro de cancelar este turno?')">
                            Cancelar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
