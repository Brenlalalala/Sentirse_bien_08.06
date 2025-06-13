@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-4">
    <h2 class="text-2xl font-bold text-pink-600 mb-4">Reservar Turno</h2>

    <form method="POST" action="{{ route('cliente.turnos.store') }}">
        @csrf

        @foreach ($servicios as $servicio)
            <div class="mb-6 p-4 border rounded">
                <label>
                    <input type="checkbox" name="servicios[{{ $servicio->id }}][seleccionado]" value="1">
                    {{ $servicio->nombre }} - ${{ number_format($servicio->precio, 0, ',', '.') }}
                </label>

                <div class="mt-2">
                    <label>Fecha:</label>
                    <input type="date" name="servicios[{{ $servicio->id }}][fecha]" class="border rounded px-2 py-1">
                    <label>Hora:</label>
                    <input type="time" name="servicios[{{ $servicio->id }}][hora]" class="border rounded px-2 py-1">
                </div>
            </div>
        @endforeach

        <div class="mb-4">
            <label for="metodo_pago" class="block">Método de pago:</label>
            <select name="metodo_pago" id="metodo_pago" class="border rounded px-3 py-2">
                <option value="debito">Tarjeta de débito</option>
            </select>
        </div>

        <button type="submit" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">Reservar</button>
    </form>

</div>
@endsection
