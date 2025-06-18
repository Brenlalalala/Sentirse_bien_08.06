@extends('layouts.sidebar')

@section('content')
    <div class="mt-10">
        <h2 class="text-3xl font-bold mb-6">Turnos por día</h2>

        <!-- se modifica el formulario para seleccionar rango de  fecha-->
         <form method="GET" action="{{ route('admin.turnos.dia') }}" class="mb-6 flex flex-wrap gap-4 items-end">
             <!--Fecha Inicio -->
    <div>
        <label for="fecha_inicio" class="block text-lg font-medium">Fecha inicio:</label>
        <input
            type="date"
            name="fecha_inicio"
            id="fecha_inicio"
            value="{{ request('fecha_inicio') }}"
            class="mt-1 p-2 border rounded-md"
        >
    </div>

    <!-- Fecha Fin -->
    <div>
        <label for="fecha_fin" class="block text-lg font-medium">Fecha fin:</label>
        <input
            type="date"
            name="fecha_fin"
            id="fecha_fin"
            value="{{ request('fecha_fin') }}"
            class="mt-1 p-2 border rounded-md"
        >
    </div>

    <!-- Servicio -->
    <div>
        <label for="servicio_id" class="block text-lg font-medium">Servicio:</label>
        <select
            name="servicio_id"
            id="servicio_id"
            class="mt-1 p-2 border rounded-md"
        >
            <option value="">Todos</option>
            @foreach($servicios as $servicio)
                <option value="{{ $servicio->id }}" {{ request('servicio_id') == $servicio->id ? 'selected' : '' }}>
                    {{ $servicio->nombre }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Estado -->
    <div>
        <label for="estado" class="block text-lg font-medium">Estado:</label>
        <select
            name="estado"
            id="estado"
            class="mt-1 p-2 border rounded-md"
        >
            <option value="">Todos</option>
            <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="confirmado" {{ request('estado') == 'confirmado' ? 'selected' : '' }}>Confirmado</option>
            <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
        </select>
    </div>

    <div>
        <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded">Filtrar</button>
    </div>

    <div>
        <button
            type="submit"
            name="exportar_pdf"
            value="1"
            class="bg-gray-700 text-white px-4 py-2 rounded"
        >
            Exportar PDF
        </button>
    </div>
</form>

        <!-- Mensaje de error -->
        @if ($turnos->isEmpty())
            <p class="text-lg">No hay turnos para esta fecha.</p>
        @else

           
            @foreach ($turnos as $servicio => $listaTurnos)
                <h3 class="text-2xl font-semibold mt-8 mb-4 text-pink-600">{{ $servicio }}</h3>
                <table class="w-full table-auto border-collapse border border-gray-300 text-lg mb-6">
                    <thead>
                        <tr class="bg-pink-100">
                            <th class="border px-4 py-2">Hora</th>
                            <th class="border px-4 py-2">Cliente</th>
                            <th class="border px-4 py-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listaTurnos as $turno)
                            <tr>
                                <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($turno->hora)->format('H:i') }}</td>
                                <td class="border px-4 py-2">{{ $turno->user->name }}</td>
                                <td class="border px-4 py-2">{{ $turno->estado }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @endif

    </div>
@endsection
