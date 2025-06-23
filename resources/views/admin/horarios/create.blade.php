@extends('layouts.sidebar')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-2xl font-bold text-pink-600 mb-6">
        {{ isset($horario) ? 'Editar Horario' : 'Nuevo Horario' }}
    </h2>

    <form action="{{ isset($horario) ? route('admin.horarios.update', $horario) : route('admin.horarios.store') }}" method="POST" class="space-y-6">
        @csrf
        @if(isset($horario))
            @method('PUT')
        @endif

        <div>
            <label for="user_id" class="block font-semibold mb-1">Profesional</label>
            <select name="user_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                @foreach($profesionales as $prof)
                    <option value="{{ $prof->id }}" @if(old('user_id', $horario->user_id ?? '') == $prof->id) selected @endif>
                        {{ $prof->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="dia" class="block font-semibold mb-1">Día</label>
            <select name="dia" class="w-full border border-gray-300 rounded px-3 py-2" required>
                @foreach(['lunes','martes','miércoles','jueves','viernes','sábado','domingo'] as $dia)
                    <option value="{{ $dia }}" @if(old('dia', $horario->dia ?? '') == $dia) selected @endif>
                        {{ ucfirst($dia) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="hora_inicio" class="block font-semibold mb-1">Hora inicio</label>
                <input type="time" name="hora_inicio" class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('hora_inicio', $horario->hora_inicio ?? '') }}" required>
            </div>
            <div>
                <label for="hora_fin" class="block font-semibold mb-1">Hora fin</label>
                <input type="time" name="hora_fin" class="w-full border border-gray-300 rounded px-3 py-2"
                       value="{{ old('hora_fin', $horario->hora_fin ?? '') }}" required>
            </div>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('admin.horarios.index') }}" class="text-pink-600 hover:underline">← Volver</a>
            <button type="submit" class="bg-pink-600 text-white px-6 py-2 rounded hover:bg-pink-700">
                Guardar
            </button>
        </div>
    </form>
</div>
@endsection
