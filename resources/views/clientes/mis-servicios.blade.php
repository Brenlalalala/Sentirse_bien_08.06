@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4">

    <h2 class="text-3xl font-semibold mb-6 text-pink-600">Mis Servicios</h2>

    {{-- Servicios Pendientes --}}
    <h4 class="text-xl font-semibold text-pink-700 mb-4">Servicios Pendientes</h4>
    @if($pendientes->isEmpty())
        <div class="bg-pink-100 text-pink-800 p-4 rounded-lg mb-6">
            No hay servicios pendientes.
        </div>
    @else
        <div class="grid md:grid-cols-2 gap-6">
            @foreach($pendientes as $turno)
                <div class="bg-white shadow-md rounded-lg p-5 border-l-4 border-pink-400 hover:shadow-lg transition">
                    <h5 class="text-lg font-bold text-gray-800">{{ $turno->servicio->nombre }}</h5>
                    <p class="text-sm text-gray-600 mt-1">
                        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($turno->fecha)->format('d/m/Y') }}
                    </p>
                    <p class="text-sm text-gray-600"><strong>Hora:</strong> {{ $turno->hora }}</p>
                    <p class="text-sm text-gray-600 mb-4"><strong>Precio:</strong> ${{ number_format($turno->monto, 2) }}</p>

                    {{-- Botón Cancelar --}}
                    <form action="{{ route('cliente.turno.cancelar', $turno->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-block bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 text-sm transition">
                            Cancelar
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Historial --}}
    <h4 class="text-xl font-semibold text-pink-700 mt-10 mb-4">Historial de Servicios</h4>
    @if($realizados->isEmpty())
        <div class="bg-gray-100 text-gray-600 p-4 rounded-lg">
            No hay servicios realizados aún.
        </div>
    @else
        <div class="grid md:grid-cols-2 gap-6">
            @foreach($realizados as $turno)
                <div class="bg-gray-100 border-l-4 border-gray-400 shadow-sm rounded-lg p-5 hover:shadow-md transition">
                    <h5 class="text-lg font-bold text-gray-700">{{ $turno->servicio->nombre }}</h5>
                    <p class="text-sm text-gray-600 mt-1">
                        <strong>Fecha:</strong> {{ \Carbon\Carbon::parse($turno->fecha)->format('d/m/Y') }}
                    </p>
                    <p class="text-sm text-gray-600"><strong>Hora:</strong> {{ $turno->hora }}</p>
                    <p class="text-sm text-gray-600 mb-4"><strong>Precio:</strong> ${{ number_format($turno->monto, 2) }}</p>

                    {{-- Botón Volver a Reservar --}}
                    <a href="{{ route('cliente.reservar-turno') }}"
                        class="inline-block bg-pink-600 text-white px-4 py-2 rounded-md hover:bg-pink-700 text-sm transition">
                        Reservar nuevamente
                    </a>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
