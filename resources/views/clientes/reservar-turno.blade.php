@extends('layouts.sidebar')

@section('content')
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
                                data-servicio="{{ $servicio->id }}"
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
                                min="{{ \Carbon\Carbon::now()->addDays(2)->format('Y-m-d') }}"
                                data-servicio="{{ $servicio->id }}"
                            />

                            <label class="block text-sm text-rose-700 font-semibold mt-2">Hora (disponibles):</label>
                            <select name="servicios[{{ $servicio->id }}][hora]" id="hora_{{ $servicio->id }}" class="w-full border border-rose-300 px-3 py-2 rounded focus:ring-rose-400 focus:border-rose-400">
                                <option value="">Selecciona un profesional y fecha</option>
                            </select>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Quitamos el selector método de pago global y por fecha -->

        <!-- Resumen y contador -->
        <div class="mt-8">
            <p id="contadorServicios" class="text-rose-700 text-lg font-semibold mb-2">Servicios seleccionados: 0</p>

            <div id="resumenServicios" class="bg-rose-50 border border-rose-300 rounded p-4 space-y-2 hidden">
                <h3 class="text-rose-800 font-bold mb-2">Resumen de reservas:</h3>
                <ul id="listaResumen" class="list-disc list-inside text-rose-700 text-base"></ul>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" class="bg-rose-600 text-white px-6 py-3 text-lg rounded-lg hover:bg-rose-700 transition">
                Ir a pagar y reservar
            </button>
        </div>
    </form>
</div>

<script>
function cargarHorarios(servicioId) {
    const profesionalSelect = document.getElementById(`profesional_${servicioId}`);
    const fechaInput = document.querySelector(`input[name="servicios[${servicioId}][fecha]"]`);
    const horaSelect = document.getElementById(`hora_${servicioId}`);

    if (!profesionalSelect || !fechaInput || !horaSelect) return;

    const profesionalId = profesionalSelect.value;
    const fecha = fechaInput.value;

    if (!profesionalId || !fecha) {
        horaSelect.innerHTML = '<option value="">Selecciona un profesional y fecha</option>';
        return;
    }

    horaSelect.innerHTML = '<option value="">Cargando...</option>';

    fetch(`/horarios-disponibles?profesional_id=${profesionalId}&servicio_id=${servicioId}&fecha=${fecha}`)
        .then(response => response.json())
        .then(data => {
            horaSelect.innerHTML = '';
            if (!data.success || !data.horarios || data.horarios.length === 0) {
                horaSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
            } else {
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Selecciona un horario';
                horaSelect.appendChild(defaultOption);

                data.horarios.forEach(hora => {
                    const option = document.createElement('option');
                    option.value = hora.hora;
                    option.textContent = hora.formatted;
                    horaSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error al cargar horarios:', error);
            horaSelect.innerHTML = '<option value="">Error al cargar horarios</option>';
        });
}

function formatearFecha(fechaISO) {
    if (!fechaISO) return 'Fecha inválida';
    const partes = fechaISO.split('-'); // ["2025", "06", "27"]
    const anio = partes[0];
    const mes = partes[1];
    const dia = partes[2];
    return `${dia}/${mes}/${anio}`;
}

function actualizarUI() {
    let total = 0;
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name$="[seleccionado]"]');
    const contador = document.getElementById('contadorServicios');
    const resumenBox = document.getElementById('resumenServicios');
    const resumenLista = document.getElementById('listaResumen');

    resumenLista.innerHTML = '';

    const horarios = [];

    checkboxes.forEach((checkbox) => {
        if (checkbox.checked) {
            total++;
            const servicioId = checkbox.name.match(/\d+/)[0];
            const nombre = checkbox.closest('.border').querySelector('span').textContent;
            const fecha = document.querySelector(`input[name="servicios[${servicioId}][fecha]"]`)?.value;
            const hora = document.querySelector(`select[name="servicios[${servicioId}][hora]"]`)?.value;

            const fechaHora = `${fecha} ${hora}`;
            const item = document.createElement('li');
            const fechaFormateada = fecha ? formatearFecha(fecha) : 'sin fecha';
            item.textContent = `${nombre} - ${fechaFormateada} a las ${hora || 'sin hora'}`;

            if (horarios.includes(fechaHora)) {
                item.classList.add('text-red-600');
                item.textContent += ' ⚠️ Horario duplicado';
            } else {
                horarios.push(fechaHora);
            }
            resumenLista.appendChild(item);
        }
    });

    contador.textContent = `Servicios seleccionados: ${total}`;
    resumenBox.classList.toggle('hidden', total === 0);
}

document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name$="[seleccionado]"]');
    const selectsProfesionales = document.querySelectorAll('select[id^="profesional_"]');
    const inputsFecha = document.querySelectorAll('input[type="date"][name^="servicios["]');
    const selectsHora = document.querySelectorAll('select[id^="hora_"]');

    checkboxes.forEach(cb => cb.addEventListener('change', actualizarUI));
    selectsProfesionales.forEach(select => select.addEventListener('change', (e) => {
        const servicioId = e.target.dataset.servicio;
        if (servicioId) cargarHorarios(servicioId);
        actualizarUI();
    }));
    inputsFecha.forEach(input => input.addEventListener('change', (e) => {
        const servicioId = e.target.dataset.servicio;
        if (servicioId) cargarHorarios(servicioId);
        actualizarUI();
    }));
    selectsHora.forEach(select => select.addEventListener('change', actualizarUI));

    actualizarUI();
});
</script>

</div>
@endsection
