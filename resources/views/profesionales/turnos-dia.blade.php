@extends('layouts.sidebar')

@section('content')
    <div class="container mx-auto px-4 py-10 max-w-4xl">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Turnos del Día</h2>

        @foreach($turnos as $turno)
            <div class="bg-white shadow-lg p-6 mb-4 rounded-lg border border-gray-200">
                <p class="text-gray-700 font-medium"><strong>Cliente:</strong> {{ $turno->usuario->name }}</p>
                <p class="text-gray-700 font-medium"><strong>Hora:</strong> {{ $turno->hora }}</p>
                <p class="text-gray-700 font-medium"><strong>Servicio:</strong> {{ $turno->servicio->nombre ?? 'N/A' }}</p>

                <form method="POST" action="{{ route('profesional.historial.agregar') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $turno->user_id }}">

                    <label for="detalle" class="block mb-1 text-gray-600">¿Qué se hizo?</label>
                    <textarea name="detalle" rows="2" class="w-full border rounded-lg p-2 border-gray-300 focus:outline-none focus:ring-2 focus:ring-pink-500" required></textarea>

                    <button type="submit" class="mt-3 bg-pink-500 text-white px-4 py-2 rounded-lg hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-300">
                        Guardar al Historial
                    </button>
                </form>

                <a href="{{ route('profesional.historial.ver', $turno->user_id) }}" class="inline-block mt-2 text-blue-500 hover:underline">
                    Ver historial
                </a>
            </div>
        @endforeach
    </div>
@endsection
