@extends('layouts.sidebar')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold text-pink-600 mb-6">Gestión de Turnos</h2>

    <table class="w-full table-auto border-collapse">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">Cliente</th>
                <th class="p-2 border">Servicio</th>
                <th class="p-2 border">Fecha</th>
                <th class="p-2 border">Hora</th>
                <th class="p-2 border">Estado</th>
                <th class="p-2 border">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($turnos as $turno)
            <tr>
                <td class="p-2 border">{{ $turno->user->name ?? 'Desconocido' }}</td>
                <td class="p-2 border">{{ $turno->servicio->nombre ?? 'N/A' }}</td>
                <td class="p-2 border">{{ $turno->fecha }}</td>
                <td class="p-2 border">{{ $turno->hora }}</td>
                <td class="p-2 border">{{ ucfirst($turno->estado) }}</td>
                <td class="p-2 border">
                    {{-- Próximamente: Editar y Cancelar --}}
                    <a href="#" class="text-blue-600 hover:underline">Editar</a>
                    <a href="#" class="text-red-600 hover:underline ml-2">Cancelar</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
