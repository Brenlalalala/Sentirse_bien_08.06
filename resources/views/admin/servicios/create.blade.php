@extends('layouts.sidebar')

@section('content')
<div class="container mx-auto px-4 py-10 max-w-2xl">
    <h1 class="text-3xl font-bold text-pink-600 mb-6">Nuevo Servicio</h1>

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

    <form action="{{ route('admin.servicios.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded-xl shadow">
        @csrf

        <input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full border px-4 py-2 rounded" placeholder="Nombre" required>
        <input type="text" name="categoria" value="{{ old('categoria') }}" class="w-full border px-4 py-2 rounded" placeholder="Categoría" required>
        <input type="text" name="subcategoria" value="{{ old('subcategoria') }}" class="w-full border px-4 py-2 rounded" placeholder="Subcategoría" required>
        <input type="number" name="precio" step="0.01" min="0" value="{{ old('precio') }}" class="w-full border px-4 py-2 rounded" placeholder="Precio" required>
        <textarea name="descripcion" rows="4" class="w-full border px-4 py-2 rounded" placeholder="Descripción">{{ old('descripcion') }}</textarea>
        <input type="file" name="imagen" class="w-full border px-4 py-2 rounded">

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.servicios.index') }}" class="text-pink-600 hover:underline">← Cancelar</a>
            <button type="submit" class="bg-pink-600 text-white px-6 py-2 rounded hover:bg-pink-700 transition">Guardar</button>
        </div>
    </form>
</div>
@endsection
