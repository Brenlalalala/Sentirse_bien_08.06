@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">Reservar Turno</h2>

    <form id="form-reserva" method="POST" action="{{ route('cliente.reservar-turno') }}">
        @csrf

        <!-- Servicios -->
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-2">Seleccionar Servicios</label>
            @foreach ($servicios as $servicio)
                <div>
                    <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}" id="servicio_{{ $servicio->id }}">
                    <label for="servicio_{{ $servicio->id }}">{{ $servicio->nombre }} - ${{ $servicio->precio }}</label>
                </div>
            @endforeach
        </div>

        <!-- Fecha -->
        <div class="mb-4">
            <label for="fecha" class="block font-semibold">Seleccionar Fecha</label>
            <input type="date" name="fecha" id="fecha" class="border p-2 w-full" min="{{ \Carbon\Carbon::now()->addDays(2)->format('Y-m-d') }}" required>
        </div>

        <!-- Hora -->
        <div class="mb-4">
            <label for="hora" class="block font-semibold">Seleccionar Hora</label>
            <input type="time" name="hora" id="hora" class="border p-2 w-full" required>
        </div>

        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Confirmar Reserva</button>
    </form>
</div>
@endsection
