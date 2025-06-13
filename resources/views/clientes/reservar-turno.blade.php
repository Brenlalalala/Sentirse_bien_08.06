@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white/80 rounded-xl shadow-md">

    <h2 class="text-3xl font-bold text-pink-600 mb-6 text-center">Reservar Turno</h2>

    {{-- Mensajes --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('cliente.turnos.store') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($servicios as $servicio)
                <div class="border rounded-lg p-4 bg-white shadow hover:shadow-md transition">
                    <label class="flex items-center gap-2 font-semibold">
                        <input type="checkbox" name="servicios[{{ $servicio->id }}][seleccionado]" value="1" class="servicio-checkbox text-pink-600">
                        {{ $servicio->nombre }} — ${{ number_format($servicio->precio, 0, ',', '.') }}
                    </label>

                    <div class="hidden mt-4 servicio-inputs">
                        <label class="block mb-2 text-sm">Fecha:</label>
                        <input type="date" name="servicios[{{ $servicio->id }}][fecha]" class="w-full border rounded px-2 py-1 mb-2">

                        <label class="block mb-2 text-sm">Hora:</label>
                        <input type="time" name="servicios[{{ $servicio->id }}][hora]" class="w-full border rounded px-2 py-1" value="{{ old('servicios.' . $servicio->id . '.hora') }}">
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            <label for="metodo_pago" class="block font-semibold mb-2">Método de pago:</label>
            <select name="metodo_pago" id="metodo_pago" class="border rounded px-3 py-2 w-full">
                <option value="">-- Seleccioná una opción --</option>
                <option value="debito">Tarjeta de débito</option>
                {{-- Agregá más si tenés --}}
            </select>
        </div>

        <div class="mt-6 text-center">
            <button type="submit" class="bg-pink-500 text-white px-6 py-2 rounded hover:bg-pink-600 transition">
                Confirmar Reserva
            </button>
        </div>
    </form>
</div>

{{-- Script para mostrar campos según checkbox --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.servicio-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const inputs = this.closest('div').querySelector('.servicio-inputs');
                if (this.checked) {
                    inputs.classList.remove('hidden');
                } else {
                    inputs.classList.add('hidden');
                    // También podrías limpiar los valores si querés
                    inputs.querySelectorAll('input').forEach(input => input.value = '');
                }
            });
        });
    });

    document.querySelector('form').addEventListener('submit', function(event) {
    const horas = document.querySelectorAll('input[type="time"]');
    let valid = true;

    horas.forEach(function(horaInput) {
        const horaValue = horaInput.value;
        // Valida si la hora está en formato HH:MM
        const regex = /^[0-2][0-9]:[0-5][0-9]$/;
        if (!regex.test(horaValue)) {
            valid = false;
            alert('La hora debe tener el formato HH:MM');
        }
    });

    if (!valid) {
        event.preventDefault(); // Evita el envío del formulario si hay un error
    }
});

</script>
@endsection
