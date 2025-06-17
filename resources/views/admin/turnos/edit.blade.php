@extends('layouts.sidebar')

@section('content')
<div class="max-w-xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-xl font-bold mb-4">Editar Turno</h2>

    <form action="{{ route('admin.turnos.update', $turno->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="hora" class="block font-medium">Hora:</label>
            <input type="time" name="hora" id="hora" value="{{ $turno->hora }}" class="border rounded w-full p-2">
        </div>

        <div class="mb-4">
            <label for="servicio_id" class="block font-medium">Servicio:</label>
            <select name="servicio_id" class="border rounded w-full p-2">
                @foreach($servicios as $servicio)
                    <option value="{{ $servicio->id }}" {{ $turno->servicio_id == $servicio->id ? 'selected' : '' }}>
                        {{ $servicio->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="estado" class="block font-medium">Estado:</label>
            <select name="estado" class="border rounded w-full p-2">
                @foreach(['pendiente', 'confirmado', 'cancelado'] as $estado)
                    <option value="{{ $estado }}" {{ $turno->estado == $estado ? 'selected' : '' }}>
                        {{ ucfirst($estado) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="profesional_id" class="block font-medium">Profesional asignado:</label>
            <select name="profesional_id" class="border rounded w-full p-2">
                <option value="">-- Sin asignar --</option>
                @foreach($profesionales as $profesional)
                    <option value="{{ $profesional->id }}" {{ $turno->profesional_id == $profesional->id ? 'selected' : '' }}>
                        {{ $profesional->name }}
                    </option>
                @endforeach
            </select>
        </div>


        <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700">Guardar cambios</button>
    </form>
</div>
@endsection
