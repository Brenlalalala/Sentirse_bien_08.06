<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Historial de {{ $cliente->name }}</h2>
    </x-slot>

    <div class="py-6">
        @forelse($historial as $registro)
            <div class="bg-white shadow p-4 mb-4 rounded">
                <p><strong>Fecha:</strong> {{ $registro->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Profesional:</strong> {{ $registro->profesional->name }}</p>
                <p><strong>Detalle:</strong> {{ $registro->detalle }}</p>
            </div>
        @empty
            <p>No hay historial para este cliente.</p>
        @endforelse
    </div>
</x-app-layout>
