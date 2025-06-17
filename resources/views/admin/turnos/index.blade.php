@extends('layouts.sidebar')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold text-pink-600 mb-6">Gestión de Turnos</h2>

    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">
            <p class="font-bold">¡Éxito!</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <table class="w-full table-auto border-collapse">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">Servicio</th>
                <th class="p-2 border">Cliente</th>
                <th class="p-2 border">Fecha</th>
                <th class="p-2 border">Hora</th>
                <th class="p-2 border">Estado</th>
                <th class="p-2 border">Profesional</th>
                <th class="p-2 border">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($turnos as $turno)
            <tr>
                <td class="p-2 border">{{ $turno->servicio->nombre ?? 'N/A' }}</td>
                <td class="p-2 border">{{ $turno->user->name ?? 'Desconocido' }}</td>
                <td class="p-2 border">
                    {{ ucfirst(\Carbon\Carbon::parse($turno->fecha)->locale('es')->translatedFormat('l d \d\e F \d\e Y')) }}
                </td>
                <td class="p-2 border">{{ \Carbon\Carbon::parse($turno->hora)->format('H:i') }}</td>
                <td class="p-2 border">{{ ucfirst($turno->estado) }}</td>
                <td class="p-2 border">{{ $turno->profesional->name ?? 'No asignado' }}</td>
                <td class="p-2 border">
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
