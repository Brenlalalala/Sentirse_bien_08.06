@extends('layouts.sidebar')

@section('content')
<div class="container mx-auto px-4 py-10 max-w-2xl">
    <h1 class="text-3xl font-bold text-pink-600 mb-6">Editar Servicio</h1>

    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            <strong>Ups!</strong> Hay errores:<br>
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.servicios.update', $servicio->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded-xl shadow">
        @csrf
        @method('PUT')

        <input type="text" name="nombre" value="{{ old('nombre', $servicio->nombre) }}" class="w-full border px-4 py-2 rounded" placeholder="Nombre" required>
        <input type="text" name="categoria" value="{{ old('categoria', $servicio->categoria) }}" class="w-full border px-4 py-2 rounded" placeholder="Categoría" required>
        <input type="text" name="subcategoria" value="{{ old('subcategoria', $servicio->subcategoria) }}" class="w-full border px-4 py-2 rounded" placeholder="Subcategoría" required>
        <input type="number" name="precio" step="0.01" min="0" value="{{ old('precio', $servicio->precio) }}" class="w-full border px-4 py-2 rounded" placeholder="Precio" required>
        <textarea name="descripcion" rows="4" class="w-full border px-4 py-2 rounded" placeholder="Descripción">{{ old('descripcion', $servicio->descripcion) }}</textarea>

        <div>
            <label for="profesionales" class="block text-gray-700 font-semibold mb-2">Asignar Profesionales:</label>
            <select 
                name="profesionales[]" 
                id="profesionales" 
                multiple 
                class="w-full border px-4 py-2 rounded bg-white text-gray-700"
            >
                @foreach($profesionales as $profesional)
                    <option 
                        value="{{ $profesional->id }}" 
                        @if($servicio->profesionales->contains($profesional->id)) selected @endif
                    >
                        {{ $profesional->name }}
                    </option>
                @endforeach
            </select>
            <small class="text-gray-500 italic">Usá Ctrl (Windows) o Cmd (Mac) para seleccionar múltiples.</small>
        </div>

        
        
        <div>
            <label class="block mb-2 text-gray-700">Imagen actual:</label>
            @if($servicio->imagen)
                <img src="{{ asset($servicio->imagen) }}" alt="Imagen actual" class="w-32 h-32 object-cover mb-2 rounded border">
            @else
                <p class="text-gray-400 italic">No hay imagen cargada.</p>
            @endif
        </div>

        <input type="file" name="imagen" class="w-full border px-4 py-2 rounded">

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.servicios.index') }}" class="text-pink-600 hover:underline">← Cancelar</a>
            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">Actualizar</button>
        </div>
    </form>
</div>
@endsection
