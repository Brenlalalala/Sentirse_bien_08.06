@extends('layouts.app')

@section('content')
    <div class="mt-10">
        <h2 class="text-3xl font-bold mb-6">Turnos por día</h2>

        <form method="GET" action="{{ route('admin.turnos.dia') }}" class="mb-6">
            <label for="fecha" class="block text-lg font-medium text-gray-700">Seleccionar fecha:</label>
            <input type="date" name="fecha" id="fecha" value="{{ $fecha }}"
                   class="mt-2 p-3 border rounded-md text-lg" onchange="this.form.submit()">
        </form>

        @if ($turnos->isEmpty())
            <p class="text-lg">No hay turnos para esta fecha.</p>
        @else
            <table class="w-full table-auto border-collapse border border-gray-300 text-lg">
                <thead>
                    <tr class="bg-pink-100">
                        <th class="border px-4 py-2">Hora</th>
                        <th class="border px-4 py-2">Cliente</th>
                        <th class="border px-4 py-2">Servicio</th>
                        <th class="border px-4 py-2">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($turnos as $turno)
                        <tr>
                            <td class="border px-4 py-2">{{ $turno->hora }}</td>
                            <td class="border px-4 py-2">{{ $turno->user->name }}</td>
                            <td class="border px-4 py-2">{{ $turno->servicio->nombre }}</td>
                            <td class="border px-4 py-2">{{ $turno->estado }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
