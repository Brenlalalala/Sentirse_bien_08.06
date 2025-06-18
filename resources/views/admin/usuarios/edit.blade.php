@extends('layouts.sidebar')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold text-pink-600 mb-6">Editar Usuario</h2>

    <form action="{{ route('admin.usuarios.update', $user) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block font-semibold mb-1">Nombre</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                   class="w-full border border-gray-300 rounded px-3 py-2">
            @error('name')
                <p class="text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block font-semibold mb-1">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                   class="w-full border border-gray-300 rounded px-3 py-2">
            @error('email')
                <p class="text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block font-semibold mb-1">Contraseña <small>(dejar vacío para no cambiar)</small></label>
            <input type="password" name="password" id="password"
                   class="w-full border border-gray-300 rounded px-3 py-2">
            @error('password')
                <p class="text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="role" class="block font-semibold mb-1">Rol</label>
            <select name="role" id="role" required class="w-full border border-gray-300 rounded px-3 py-2">
                <option value="">Seleccione un rol</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}" {{ (old('role', $user->roles->pluck('name')->first()) == $role->name) ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
            @error('role')
                <p class="text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="bg-pink-600 text-white px-6 py-2 rounded hover:bg-pink-700">Actualizar Usuario</button>
    </form>
</div>
@endsection
