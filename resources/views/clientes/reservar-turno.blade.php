@extends('layouts.sidebar')

@section('content')
    <a href="{{ route('dashboard') }}" class="inline-block bg-pink-400 text-white px-1 py-1 rounded hover:bg-pink-500 mb-4">
        ← Volver a Inicio
    </a>
<div class="max-w-5xl mx-auto p-2 rounded-xl shadow-md">

<div class="container mx-auto px-2 py-2">
    <h1 class="text-3xl font-bold text-rose-600 mb-6">Reservar Turnos</h1>

    @if ($errors->any())
        <div class="bg-rose-100 text-rose-800 p-4 rounded mb-6 border border-rose-300">
            <strong>¡Ups!</strong> Por favor corrige los siguientes errores:
            <ul class="list-disc pl-6 mt-2">
                @foreach ($errors->all() as $error)
                    <li class="text-base">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cliente.turnos.store') }}" method="POST" class="space-y-8">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($servicios as $servicio)
                @php
                    $rutaStorageRelativa = $servicio->imagen;
                    $rutaStorageCompleta = public_path($rutaStorageRelativa);
                    $rutaAlternativa = 'imagenes/' . \Illuminate\Support\Str::slug($servicio->nombre) . '.jpg';
                    $rutaAlternativaCompleta = public_path($rutaAlternativa);

                    if ($servicio->imagen && file_exists($rutaStorageCompleta)) {
                        $rutaFinal = asset($rutaStorageRelativa);
                    } elseif (file_exists($rutaAlternativaCompleta)) {
                        $rutaFinal = asset($rutaAlternativa);
                    } else {
                        $rutaFinal = null;
                    }
                @endphp

                <div class="bg-white border border-rose-200 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                    <label class="flex items-center p-4">
                        <input type="checkbox" name="servicios[{{ $servicio->id }}][seleccionado]" value="1" class="form-checkbox text-rose-600 mr-2">
                        <span class="text-xl font-semibold text-rose-700">{{ $servicio->nombre }}</span>
                    </label>

                    @if($rutaFinal)
                        <img src="{{ $rutaFinal }}" alt="{{ $servicio->nombre }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500 text-lg">
                            Sin imagen
                        </div>
                    @endif

                    <div class="p-4 space-y-3">
                        <p class="text-base text-gray-700 font-medium leading-snug">{{ $servicio->descripcion }}</p>
                        <p class="text-lg font-bold text-rose-600">${{ number_format($servicio->precio, 2) }}</p>

               {{-- Selector de profesional --}}
            @if($servicio->profesionales->count() > 0)
                <label class="block mb-1 text-pink-500 font-semibold" for="profesional_{{ $servicio->id }}">
                    Seleccioná profesional {{ $servicio->name }}
                </label>
                <select 
                    id="profesional_{{ $servicio->id }}" 
                    name="servicios[{{ $servicio->id }}][profesional_id]" 
                    class="border border-pink-300 rounded p-2 w-full mb-3"
                >
                    @foreach($servicio->profesionales as $profesional)
                                <option value="{{ $profesional->id }}">
                                    {{ $profesional->name }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <p class="text-sm text-gray-400">No hay profesionales disponibles para este servicio.</p>
                    @endif

                        <div class="space-y-2 mt-4">
                            <label class="block text-sm text-rose-700 font-semibold">Fecha (mínimo 48hs):</label>
                            <input type="date" name="servicios[{{ $servicio->id }}][fecha]"
                                class="w-full border border-rose-300 px-3 py-2 rounded focus:ring-rose-400 focus:border-rose-400"
                                min="{{ \Carbon\Carbon::now()->addDays(2)->format('Y-m-d') }}">

                            <label class="block text-sm text-rose-700 font-semibold mt-2">Hora (de 08 a 17hs):</label>
                            <select name="servicios[{{ $servicio->id }}][hora]" class="w-full border border-rose-300 px-3 py-2 rounded focus:ring-rose-400 focus:border-rose-400">
                                @for ($h = 8; $h <= 17; $h++)
                                    <option value="{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00">
                                        {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!--  Método de pago mixeado -->
        <div class="mt-10">
            <label for="metodo_pago" class="block mb-2 text-lg font-semibold text-rose-800">Método de pago:</label>
            <select name="metodo_pago" id="metodo_pago" class="w-full border border-rose-300 px-4 py-3 rounded-lg focus:ring-rose-500 focus:border-rose-500" required>
                <option value="">Seleccionar</option>
                <option value="debito">Débito (15% de descuento si se paga anticipado)</option>
                <option value="credito">Tarjeta de crédito</option>
                <option value="efectivo">Efectivo</option>
            </select>
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" class="bg-rose-600 text-white px-6 py-3 text-lg rounded-lg hover:bg-rose-700 transition">
                Reservar Turnos
            </button>
        </div>

        <!-- Resumen y contador -->
        <div class="mt-8">
            <p id="contadorServicios" class="text-rose-700 text-lg font-semibold mb-2">Servicios seleccionados: 0</p>

            <div id="resumenServicios" class="bg-rose-50 border border-rose-300 rounded p-4 space-y-2 hidden">
                <h3 class="text-rose-800 font-bold mb-2">Resumen de reservas:</h3>
                <ul id="listaResumen" class="list-disc list-inside text-rose-700 text-base"></ul>
            </div>
        </div>

    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name$="[seleccionado]"]');
        const contador = document.getElementById('contadorServicios');
        const resumenBox = document.getElementById('resumenServicios');
        const resumenLista = document.getElementById('listaResumen');

        function actualizarUI() {
            let total = 0;
            resumenLista.innerHTML = '';

            const horarios = [];

            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    total++;

                    const servicioId = checkbox.name.match(/\d+/)[0];
                    const nombre = checkbox.closest('.border').querySelector('span').textContent;
                    const fecha = document.querySelector(`input[name="servicios[${servicioId}][fecha]"]`)?.value;
                    const hora = document.querySelector(`select[name="servicios[${servicioId}][hora]"]`)?.value;

                    // Agregar al resumen
                    const item = document.createElement('li');
                    item.textContent = `${nombre} - ${fecha || 'sin fecha'} a las ${hora || 'sin hora'}`;
                    resumenLista.appendChild(item);

                    // Validar superposición
                    const fechaHora = `${fecha} ${hora}`;
                    if (horarios.includes(fechaHora)) {
                        item.classList.add('text-red-600');
                        item.textContent += ' ⚠️ Horario duplicado';
                    } else {
                        horarios.push(fechaHora);
                    }
                }
            });

            contador.textContent = `Servicios seleccionados: ${total}`;
            resumenBox.classList.toggle('hidden', total === 0);
        }

        // Eventos
        checkboxes.forEach(cb => cb.addEventListener('change', actualizarUI));
        document.querySelectorAll('input[type="date"], select').forEach(el => el.addEventListener('change', actualizarUI));
    });
</script>

@endsection
