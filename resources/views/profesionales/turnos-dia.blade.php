<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Turnos del Día</h2>
    </x-slot>

    <div class="py-6">
        @foreach($turnos as $turno)
            <div class="bg-white shadow p-4 mb-4 rounded">
                <p><strong>Cliente:</strong> {{ $turno->usuario->name }}</p>
                <p><strong>Hora:</strong> {{ $turno->hora }}</p>
                <p><strong>Servicio:</strong> {{ $turno->servicio->nombre ?? 'N/A' }}</p>

                <form method="POST" action="{{ route('profesional.historial.agregar') }}" class="mt-4">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $turno->user_id }}">
                    <label for="detalle" class="block mb-1">¿Qué se hizo?</label>
                    <textarea name="detalle" rows="2" class="w-full border rounded p-2" required></textarea>
                    <button type="submit" class="mt-2 bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">Guardar al Historial</button>
                </form>

                <a href="{{ route('profesional.historial.ver', $turno->user_id) }}" class="inline-block mt-2 text-blue-500 hover:underline">Ver historial</a>
            </div>
        @endforeach
    </div>
</x-app-layout>
