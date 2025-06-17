@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-pink-600">Servicios</h1>
        <a href="{{ route('admin.servicios.create') }}" class="bg-pink-600 text-white px-4 py-2 rounded hover:bg-pink-700 transition">
            + Nuevo Servicio
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($servicios as $servicio)
             @php
             
            // Genero el nombre del archivo y la ruta pública
            $nombreArchivoImagen = Str::slug($servicio->nombre) . '.jpg';
            $rutaImagen = asset('imagenes/' . $nombreArchivoImagen);
            // Ruta del archivo físico para verificar si existe (opcional)
            $rutaArchivoFisico = public_path('imagenes/' . $nombreArchivoImagen);
        @endphp

        <div class="bg-white rounded-xl shadow p-4">
            @if(file_exists($rutaArchivoFisico))
                <img src="{{ $rutaImagen }}" alt="{{ $servicio->nombre }}" class="w-full h-40 object-cover rounded mb-3">
            @else
                <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-400 mb-3 rounded">
                    Sin imagen
                </div>
            @endif

                <h2 class="text-xl font-semibold text-pink-700">{{ $servicio->nombre }}</h2>
                <p class="text-gray-600 text-sm mb-2">{{ $servicio->categoria }} > {{ $servicio->subcategoria }}</p>
                <p class="text-gray-700 mb-2">{{ $servicio->descripcion }}</p>
                <p class="text-green-600 font-bold mb-3">$ {{ number_format($servicio->precio, 2) }}</p>

                <div class="flex justify-between">
                    <a href="{{ route('admin.servicios.edit', $servicio->id) }}" class="text-yellow-600 hover:underline">Editar</a>

                    <form action="{{ route('admin.servicios.destroy', $servicio->id) }}" method="POST" onsubmit="return confirm('¿Eliminar este servicio?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="col-span-3 text-center text-gray-500">No hay servicios cargados aún.</p>
        @endforelse
    </div>
</div>
@endsection
