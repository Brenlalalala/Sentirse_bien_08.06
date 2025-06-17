@extends('layouts.sidebar')

@section('content')
    <div class="container mx-auto px-4 py-10 max-w-4xl">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Historial de {{ $cliente->name }}</h2>

        @forelse($historial as $registro)
            <div class="bg-white shadow-lg p-6 mb-4 rounded-lg border border-gray-200">
                <p class="text-gray-700 font-medium"><strong>Fecha:</strong> {{ $registro->created_at->format('d/m/Y H:i') }}</p>
                <p class="text-gray-700 font-medium"><strong>Profesional:</strong> {{ $registro->profesional->name }}</p>
                <p class="mt-2 text-gray-600"><strong>Detalle:</strong> {{ $registro->detalle }}</p>
            </div>
        @empty
            <p class="text-gray-500 text-center">No hay historial para este cliente.</p>
        @endforelse
    </div>
@endsection
