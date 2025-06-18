@extends('layouts.sidebar')

@section('content')
<div class="max-w-6xl mx-auto p-6 shadow rounded">
    <h2 class="text-2xl font-bold text-pink-600 mb-6">Gestión de Usuarios</h2>

    @if(session('success'))
        <div id="mensaje-exito" class="bg-green-100 text-green-800 px-4 py-2 rounded mb-6">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(function() {
                var mensaje = document.getElementById('mensaje-exito');
                if (mensaje) {
                    mensaje.style.display = 'none';
                }
            }, 3000);
        </script>
    @endif

    <a href="{{ route('admin.usuarios.create') }}" class="bg-green-100 text-gray px-4 py-2 rounded mb-4 inline-block hover:bg-blue-500 transition">
        ➕ Crear nuevo usuario
    </a>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded shadow mb-8">
                <thead class="bg-pink-100 text-pink-700 uppercase text-sm leading-normal">
                     <tr>
                        <th class="py-3 px-6 text-left">Nombre</th>
                        <th class="py-3 px-6 text-left">Email</th>
                        <th class="py-3 px-6 text-left">Rol</th>
                        <th class="py-3 px-6 text-left">Acciones</th>
                    </tr>
                </thead>
            <tbody class="text-gray-700 text-sm">
            @foreach ($usuarios as $usuario)
                <tr class="border-b border-gray-200 hover:bg-pink-50 transition">
                    <td class="py-3 px-6 whitespace-nowrap">{{ $usuario->name }}</td>
                    <td class="py-3 px-6 whitespace-nowrap">{{ $usuario->email }}</td>
                    <td class="py-3 px-6 whitespace-nowrap">{{ $usuario->roles->pluck('name')->join(', ') }}</td>
                    <td class="py-3 px-6 whitespace-nowrap">
                        <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="text-blue-600 hover:underline mr-3">Editar</a>
                        <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar usuario?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
