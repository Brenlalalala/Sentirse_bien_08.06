@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-6 bg-white shadow-md rounded-lg">
    <h2 class="text-3xl font-extrabold text-pink-700 mb-8 border-b-4 border-pink-500 pb-2">
        Gestión de Servicios
    </h2>

    {{-- Botón para mostrar formulario --}}
    <button onclick="document.getElementById('formulario-servicio').classList.toggle('hidden')"
        class="mb-6 bg-pink-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-pink-700 transition duration-300 flex items-center gap-2 font-semibold">
        <span class="text-xl">➕</span> Agregar Nuevo Servicio
    </button>

    {{-- Formulario de creación (inicialmente oculto) --}}
    <div id="formulario-servicio" class="hidden mb-10 bg-pink-50 p-6 rounded-lg shadow-sm border border-pink-300">
        <form action="{{ route('admin.servicios.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @csrf

            <input type="text" name="nombre" placeholder="Nombre" class="border border-pink-400 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" required>
            <input type="text" name="categoria" placeholder="Categoría" class="border border-pink-400 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" required>
            <input type="text" name="subcategoria" placeholder="Subcategoría" class="border border-pink-400 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" required>
            <input type="number" name="precio" step="0.01" placeholder="Precio" class="border border-pink-400 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" required>
            <textarea name="descripcion" placeholder="Descripción" class="border border-pink-400 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500 md:col-span-2" rows="4"></textarea>
            <input type="file" name="imagen" class="md:col-span-2">
            
            <button type="submit" class="md:col-span-2 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg hover:bg-green-700 transition duration-300 font-semibold">
                Guardar Servicio
            </button>
        </form>
    </div>

    {{-- Tabla de servicios --}}
    <div class="overflow-x-auto rounded-lg border border-pink-300 shadow-sm">
        <table class="w-full table-auto border-collapse text-gray-900">
            <thead class="bg-pink-300 text-pink-900 font-semibold uppercase text-sm">
                <tr>
                    <th class="p-4 border border-pink-400">Nombre</th>
                    <th class="p-4 border border-pink-400">Categoría</th>
                    <th class="p-4 border border-pink-400">Subcategoría</th>
                    <th class="p-4 border border-pink-400 text-right">Precio</th>
                    <th class="p-4 border border-pink-400 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach ($servicios as $servicio)
                <tr class="hover:bg-pink-50 transition-colors">
                    <td class="p-4 border border-pink-200">{{ $servicio->nombre }}</td>
                    <td class="p-4 border border-pink-200">{{ $servicio->categoria }}</td>
                    <td class="p-4 border border-pink-200">{{ $servicio->subcategoria }}</td>
                    <td class="p-4 border border-pink-200 text-right">$ {{ number_format($servicio->precio, 2) }}</td>
                    <td class="p-4 border border-pink-200 flex justify-center space-x-3">
                        {{-- Botón Editar --}}
                        <a href="#" 
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition duration-300 text-sm font-medium select-none">
                            Editar
                        </a>

                        {{-- Botón Eliminar --}}
                        <form action="{{ route('admin.servicios.destroy', $servicio->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este servicio?')">
                            @csrf
                            @method('DELETE')
                            <button 
                              type="submit"
                              class="bg-pink-600 text-white px-4 py-2 rounded-lg shadow hover:bg-pink-700 transition duration-300 text-sm font-medium select-none">
                                Eliminar
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach

                @if ($servicios->isEmpty())
                <tr>
                    <td colspan="5" class="text-center p-6 text-gray-400 italic">No hay servicios registrados.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
