@extends('layouts.sidebar')

@section('content')
<div class="max-w-6xl mx-auto p-8 bg-white rounded-lg shadow-lg">

    <h2 class="text-3xl font-extrabold text-pink-600 mb-8 border-b-4 border-pink-400 pb-2">
        Buscar Cliente para ver Historial
    </h2>

    <!-- Formulario de búsqueda con max-width menor -->
    <form method="GET" action="{{ route('profesional.historial.ver') }}" class="mb-10 flex max-w-sm gap-3">
        <input 
            type="text" 
            name="cliente_nombre" 
            placeholder="Nombre del cliente" 
            value="{{ request('cliente_nombre') }}"
            required
            class="flex-grow border border-pink-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-pink-500 transition"
        >
        <button 
            type="submit"
            class="bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 rounded-lg shadow-md transition"
        >
            Buscar
        </button>
    </form>

    @if(session('success'))
        <div class="mb-8 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md shadow">
            {{ session('success') }}
        </div>
    @endif


    @if($clientes->count() > 1 && !$cliente)
    <h3 class="text-lg font-semibold text-pink-600 mb-4">Coincidencias encontradas:</h3>
    <ul class="mb-10 grid gap-3">
        @foreach ($clientes as $c)
            <li class="bg-pink-50 border border-pink-200 rounded-lg px-4 py-3 shadow hover:bg-pink-100 transition">
                <a href="{{ route('profesional.historial.ver', ['cliente_nombre' => $c->name, 'cliente_id' => $c->id]) }}"
                   class="text-pink-700 font-medium hover:underline block">
                    {{ $c->name }}
                    <span class="block text-sm text-gray-600">{{ $c->email }}</span>
                </a>
            </li>
        @endforeach
    </ul>
@endif

    @if($cliente)
        <!-- Mayor margen arriba para separar -->
        <h3 class="text-2xl font-bold text-pink-700 mb-8 mt-12">
            Historial completo de <span class="underline">{{ $cliente->name }}</span>
        </h3>

        {{-- Turnos realizados --}}
        <section class="mb-12">
            <h4 class="text-xl font-semibold text-pink-600 mb-3 border-b border-pink-300 pb-1">Turnos realizados</h4>

            @if($turnosRealizados->isEmpty())
                <p class="text-gray-600 italic">Este cliente no tiene turnos realizados.</p>
            @else
                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full bg-white text-center">
                        <thead class="bg-pink-100 text-pink-800 font-semibold uppercase">
                            <tr>
                                <th class="py-3 px-5 border border-pink-200">Servicio</th>
                                <th class="py-3 px-5 border border-pink-200">Fecha</th>
                                <th class="py-3 px-5 border border-pink-200">Hora</th>
                                <th class="py-3 px-5 border border-pink-200">Profesional</th>
                                <th class="py-3 px-5 border border-pink-200">Monto</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach ($turnosRealizados as $turno)
                                <tr class="even:bg-pink-50 hover:bg-pink-100 transition">
                                    <td class="py-3 px-5 border border-pink-200">{{ $turno->servicio->nombre }}</td>
                                    <td class="py-3 px-5 border border-pink-200">{{ \Carbon\Carbon::parse($turno->fecha)->format('d/m/Y') }}</td>
                                    <td class="py-3 px-5 border border-pink-200">{{ \Carbon\Carbon::parse($turno->hora)->format('H:i') }}</td>
                                    <td class="py-3 px-5 border border-pink-200">{{ $turno->profesional->name }}</td>
                                    <td class="py-3 px-5 border border-pink-200">${{ number_format($turno->monto ?? 0, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- Historial --}}
        <section class="mb-12">
            <h4 class="text-xl font-semibold text-pink-600 mb-3 border-b border-pink-300 pb-1">Historial de servicios agregados</h4>

            @if($historial->isEmpty())
                <p class="text-gray-600 italic">No hay registros en el historial.</p>
            @else
                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="min-w-full bg-white text-center">
                        <thead class="bg-pink-100 text-pink-800 font-semibold uppercase">
                            <tr>
                                <th class="py-3 px-5 border border-pink-200">Servicio</th>
                                <th class="py-3 px-5 border border-pink-200">Fecha</th>
                                <th class="py-3 px-5 border border-pink-200">Hora</th>
                                <th class="py-3 px-5 border border-pink-200">Profesional</th>
                                <th class="py-3 px-5 border border-pink-200">Detalle</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach ($historial as $item)
                                <tr class="even:bg-pink-50 hover:bg-pink-100 transition">
                                    <td class="py-3 px-5 border border-pink-200">{{ $item->servicio->nombre ?? 'Sin servicio' }}</td>
                                    <td class="py-3 px-5 border border-pink-200">{{ \Carbon\Carbon::parse($item->fecha ?? $item->created_at)->format('d/m/Y') }}</td>
                                    <td class="py-3 px-5 border border-pink-200">{{ \Carbon\Carbon::parse($item->hora ?? $item->created_at)->format('H:i') }}</td>
                                    <td class="py-3 px-5 border border-pink-200">{{ $item->profesional->name ?? 'Desconocido' }}</td>
                                    <td class="py-3 px-5 border border-pink-200">{{ $item->detalle }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

        {{-- Formulario agregar historial --}}
        <section class="mb-8">
            <h4 class="text-xl font-semibold text-pink-600 mb-4 border-b border-pink-300 pb-1">Agregar nuevo registro al historial</h4>

            <form method="POST" action="{{ route('profesional.historial.agregar') }}" class="max-w-md space-y-5">
                @csrf
                <input type="hidden" name="user_id" value="{{ $cliente->id }}">

                <div>
                    <label for="turno_id" class="block text-pink-700 font-semibold mb-1">Seleccionar turno realizado:</label>
                    <select name="turno_id" id="turno_id" required
                        class="w-full border border-pink-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-pink-500 transition">
                        <option value="">-- Seleccione --</option>
                        @foreach($turnosPendientesDisponibles as $turno)
                            <option value="{{ $turno->id }}">
                                {{ $turno->servicio->nombre }} - {{ \Carbon\Carbon::parse($turno->fecha)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($turno->hora)->format('H:i') }}
                            </option>
                        @endforeach
                    </select>

                </div>

                <div>
                    <label for="detalle" class="block text-pink-700 font-semibold mb-1">Detalle adicional:</label>
                    <textarea name="detalle" id="detalle" rows="4" required
                        class="w-full border border-pink-300 rounded-lg px-4 py-2 resize-none focus:outline-none focus:ring-2 focus:ring-pink-500 transition"
                        placeholder="Agregar detalles..."></textarea>
                </div>

                <button type="submit"
                    class="bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition">
                    Agregar al historial
                </button>
            </form>
        </section>

    @elseif(request()->has('cliente_nombre'))
        <p class="text-red-600 font-semibold mt-4">No se encontró ningún cliente con ese nombre.</p>
    @endif

</div>
@endsection
