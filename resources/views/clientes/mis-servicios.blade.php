@extends('layouts.sidebar')

@section('content')
<div class="container mx-auto py-10">
    <h2 class="text-2xl font-bold text-pink-600 mb-6">Mis Servicios (Pendientes)</h2>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($turnosPendientes->isEmpty())
        <p class="text-gray-600">No tienes turnos pendientes.</p>
    @else
        <table class="w-full table-auto border-collapse shadow-lg rounded-lg overflow-hidden">
            <thead class="bg-pink-100 text-pink-800 font-semibold">
                <tr>
                    <th class="p-3 text-left border-b">Servicio</th>
                    <th class="p-3 text-left border-b">Fecha</th>
                    <th class="p-3 text-left border-b">Hora</th>
                    <th class="p-3 text-left border-b">Profesional</th>
                    <th class="p-3 text-left border-b">Acciones</th>
                    <!-- Nueva columna para el botón de descarga -->
                    <th class="p-3 text-left border-b">Comprobante</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($turnosPendientes as $turno)
                    <tr class="text-center border-t hover:bg-pink-50 transition duration-300">
                        <td class="p-3 border">{{ $turno->servicio->nombre }}</td>
                        <td class="p-3 border">{{ \Carbon\Carbon::parse($turno->fecha)->format('d/m/Y') }}</td>
                        <td class="p-3 border">{{ \Carbon\Carbon::parse($turno->hora)->format('H:i') }}</td>
                        <td class="p-3 border">{{ $turno->profesional->name ?? 'Sin profesional asignado' }}</td>
                        <td class="p-3 border">
                            <form action="{{ route('cliente.turno.cancelar', $turno) }}" method="POST" onsubmit="return confirm('¿Estás seguro de cancelar este turno?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Cancelar</button>
                            </form>
                        </td>
                        <!-- Nueva celda con el botón para descargar el comprobante de pago -->
                        <td class="p-3 border">
                            @if($turno->pago)
        <a href="{{ route('cliente.descargar.comprobante', $turno->id) }}" class="inline-flex items-center px-4 py-2 bg-pink-500 text-white text-sm font-semibold rounded-lg shadow-md hover:bg-blue-700 transition duration-200">                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8l-8 8-8-8"/>
                                    </svg>
                                    Descargar
                                </a>

                            @else
                                <span class="text-gray-400">No disponible</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
