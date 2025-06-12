@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-2xl font-bold text-pink-700 mb-6">Editar Servicio</h2>

    <form action="{{ route('admin.servicios.update', $servicio->id) }}" method="POST" enctype="multipart/form-data" class="grid gap-4">
        @csrf
        @method('PUT')

        <input type="text" name="nombre" value="{{ old('nombre', $servicio->nombre) }}" required class="p-3 border rounded" placeholder="Nombre">
        <input type="text" name="categoria" value="{{ old('categoria', $servicio->categoria) }}" required class="p-3 border rounded" placeholder="Categoría">
        <input type="text" name="subcategoria" value="{{ old('subcategoria', $servicio->subcategoria) }}" required class="p-3 border rounded" placeholder="Subcategoría">
        <input type="number" step="0.01" name="precio" value="{{ old('precio', $servicio->precio) }}" required class="p-3 border rounded" placeholder="Precio">
        <textarea name="descripcion" rows="4" class="p-3 border rounded">{{ old('descripcion', $servicio->descripcion) }}</textarea>

        <div>
            <label class="block mb-1">Imagen actual:</label>
            @if($servicio->imagen)
                <img src="{{ asset('storage/' . $servicio->imagen) }}" alt="Imagen actual" class="w-32 h-32 object-cover mb-2">
            @else
                <p class="text-gray-400 italic">No hay imagen subida.</p>
            @endif
        </div>

        <input type="file" name="imagen">

        <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700 transition">
            Actualizar Servicio
        </button>
    </form>
</div>
@endsection
