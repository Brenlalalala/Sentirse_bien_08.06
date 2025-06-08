@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold text-pink-600 mb-6">Gestión de Servicios</h2>

    {{-- Botón para mostrar formulario --}}
    <button onclick="document.getElementById('formulario-servicio').classList.toggle('hidden')"
        class="mb-4 bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">
        ➕ Agregar Nuevo Servicio
    </button>

    {{-- Formulario de creación (inicialmente oculto) --}}
    <div id="formulario-servicio" class="hidden mb-6">
        <form action="{{ route('admin.servicios.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-2 gap-4">
            @csrf

            <input type="text" name="nombre" placeholder="Nombre" class="border p-2 rounded" required>
            <input type="text" name="categoria" placeholder="Categoría" class="border p-2 rounded" required>
            <input type="text" name="subcategoria" placeholder="Subcategoría" class="border p-2 rounded" required>
            <input type="number" name="precio" step="0.01" placeholder="Precio" class="border p-2 rounded" required>
            <textarea name="descripcion" placeholder="Descripción" class="border p-2 rounded col-span-2"></textarea>
            <input type="file" name="imagen" class="col-span-2">
            
            <button type="submit" class="col-span-2 bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                Guardar Servicio
            </button>
        </form>
    </div>

    {{-- Tabla de servicios --}}
    <table class="w-full table-auto border-collapse">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="p-2 border">Nombre</th>
                <th class="p-2 border">Categoría</th>
                <th class="p-2 border">Subcategoría</th>
                <th class="p-2 border">Precio</th>
                <th class="p-2 border">Acciones</th>
            </tr>
        </thead>
        <tbody>
            
        
            @foreach ($servicios as $servicio)
            <tr>
                <td class="p-2 border">{{ $servicio->nombre }}</td>
                <td class="p-2 border">{{ $servicio->categoria }}</td>
                <td class="p-2 border">{{ $servicio->subcategoria }}</td>
                <td class="p-2 border">$ {{ number_format($servicio->precio, 2) }}</td>
                <td class="p-2 border flex space-x-2">
                    {{-- Botón Editar (a implementar luego) --}}
                    <a href="#" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600 text-sm">Editar</a>

                    {{-- Botón Eliminar --}}
                    <form action="{{ route('admin.servicios.destroy', $servicio->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este servicio?')">
                        @csrf
                        @method('DELETE')
                        <button class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-sm">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
