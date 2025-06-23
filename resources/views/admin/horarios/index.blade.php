@extends('layouts.sidebar')

@section('content')
<div class="max-w-6xl mx-auto p-6 shadow rounded bg-white">
    <h2 class="text-2xl font-bold text-pink-600 mb-6">Horarios de Profesionales</h2>

    @if(session('success'))
        <div id="mensaje-exito" class="bg-green-100 text-green-800 px-4 py-2 rounded mb-6">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(() => {
                const mensaje = document.getElementById('mensaje-exito');
                if (mensaje) mensaje.style.display = 'none';
            }, 3000);
        </script>
    @endif

    <a href="{{ route('admin.horarios.create') }}" class="bg-pink-100 text-pink-700 px-4 py-2 rounded inline-block hover:bg-pink-200 transition mb-4">
        ➕ Nuevo horario
    </a>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow">
            <thead class="bg-pink-100 text-pink-700 uppercase text-sm leading-normal">
                <tr>
                    <th class="py-3 px-6 text-left">Profesional</th>
                    {{-- <th class="py-3 px-6 text-left">Servicio</th> --}} {{-- ELIMINADA ESTA LÍNEA --}}
                    <th class="py-3 px-6 text-left">Día</th>
                    <th class="py-3 px-6 text-left">Desde</th>
                    <th class="py-3 px-6 text-left">Hasta</th>
                    <th class="py-3 px-6 text-left">Acciones</th>
                </tr>
            </thead>
            <tbody class="text-gray-700 text-sm">
                @forelse($horarios as $horario)
                    <tr class="border-b border-gray-200 hover:bg-pink-50 transition">
                        <td class="py-3 px-6">{{ $horario->profesional->name }}</td>
                        {{-- <td class="py-3 px-6">{{ $horario->servicio->nombre }}</td> --}} {{-- ELIMINADA ESTA LÍNEA --}}
                        <td class="py-3 px-6">{{ ucfirst($horario->dia) }}</td>
                        <td class="py-3 px-6">{{ $horario->hora_inicio }}</td>
                        <td class="py-3 px-6">{{ $horario->hora_fin }}</td>
                        <td class="py-3 px-6">
                            <a href="{{ route('admin.horarios.edit', $horario) }}" class="text-blue-600 hover:underline mr-3">Editar</a>
                            <form action="{{ route('admin.horarios.destroy', $horario) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este horario?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-400">No hay horarios cargados.</td> {{-- CAMBIADO EL COLSPAN --}}
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection