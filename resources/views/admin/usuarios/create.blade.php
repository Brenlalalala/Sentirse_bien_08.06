@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-xl font-bold text-pink-600 mb-4">Crear Nuevo Usuario</h2>

    <form action="{{ route('admin.usuarios.store') }}" method="POST">
        @csrf

        <input type="text" name="name" placeholder="Nombre" class="border p-2 w-full mb-3" required>
        <input type="email" name="email" placeholder="Email" class="border p-2 w-full mb-3" required>
        <input type="password" name="password" placeholder="Contraseña" class="border p-2 w-full mb-3" required>

        <select name="role" class="border p-2 w-full mb-3" required>
            <option value="">Seleccionar rol</option>
            @foreach ($roles as $rol)
                <option value="{{ $rol->name }}">{{ ucfirst($rol->name) }}</option>
            @endforeach
        </select>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 w-full">Guardar</button>
    </form>
</div>
@endsection
