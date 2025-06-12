@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold text-pink-600 mb-6">Gestión de Usuarios</h2>

    @section('content')
<div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold text-pink-600 mb-6">Editar Usuario</h2>

    @if(session('success'))
        <div id="mensaje-exito" style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
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


    <a href="{{ route('admin.usuarios.create') }}" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-green-600">➕ Crear nuevo usuario</a>

    <table class="w-full table-auto border-collapse">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">Nombre</th>
                <th class="p-2 border">Email</th>
                <th class="p-2 border">Rol</th>
                <th class="p-2 border">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
            <tr>
                <td class="p-2 border">{{ $usuario->name }}</td>
                <td class="p-2 border">{{ $usuario->email }}</td>
                <td class="p-2 border">{{ $usuario->roles->pluck('name')->join(', ') }}</td>
                <td class="p-2 border">
                    <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="text-blue-600 hover:underline">Editar</a>
                    <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar usuario?')">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:underline ml-2">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
