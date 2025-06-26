@extends('layouts.sidebar')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">

    @php
        $fechaGrupo = $grupo1[0]['fecha'] ?? null;
    @endphp

    <h2 class="text-2xl font-bold text-pink-600 mb-6">
        Pagar servicios del {{ \Carbon\Carbon::parse($fechaGrupo)->format('d/m/Y') }}
    </h2>

    @if(session('success'))
        <div class="mb-6 p-4 text-green-800 bg-green-100 border border-green-300 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 text-red-800 bg-red-100 border border-red-300 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Formulario único --}}
    <form id="pago-formulario" method="POST" action="{{ route('pago.procesar') }}">
        @csrf

        <input type="hidden" name="fecha_grupo" value="{{ $fechaGrupo }}">

        <ul class="mb-6 space-y-4">
            @foreach ($grupo1 as $turno)
                <li class="border border-pink-300 rounded p-4">
                    <p><strong>Servicio:</strong> {{ \App\Models\Servicio::find($turno['servicio_id'])->nombre ?? 'Servicio' }}</p>
                    <p><strong>Hora:</strong> {{ $turno['hora'] }}</p>
                    <p><strong>Profesional:</strong> {{ \App\Models\User::find($turno['profesional_id'])->name ?? 'Profesional' }}</p>

                    {{-- Campos ocultos para reenviar los datos --}}
                    <input type="hidden" name="turnos[]" value="{{ json_encode($turno) }}">
                </li>
            @endforeach
        </ul>

        {{-- Selección de método de pago --}}
        <label for="metodo_pago" class="block font-semibold text-pink-700 mb-2">Seleccioná método de pago:</label>
        <select name="metodo_pago" id="metodo_pago" required
            class="w-full border border-pink-300 rounded px-4 py-2 mb-6 focus:outline-none focus:ring-2 focus:ring-pink-500">
            <option value="">-- Seleccioná --</option>
            <option value="efectivo">Efectivo</option>
            <option value="debito">Tarjeta Débito</option>
            <option value="credito">Tarjeta Crédito</option>
        </select>

        {{-- Formulario de tarjeta (solo se muestra si es tarjeta) --}}
        <div id="formulario-tarjeta" class="hidden">
            <div id="card-wrapper" class="mb-4"></div>

            <div class="flex flex-col items-center space-y-3">
                <input type="text" name="number" placeholder="Número de tarjeta" class="w-80 px-4 py-2 rounded-md border border-gray-300">
                <input type="text" name="name" placeholder="Nombre del titular" class="w-80 px-4 py-2 rounded-md border border-gray-300">

                <div class="flex space-x-2">
                    <input type="text" name="exp_month" placeholder="MM" class="w-36 px-4 py-2 rounded-md border border-gray-300">
                    <input type="text" name="exp_year" placeholder="AA" class="w-36 px-4 py-2 rounded-md border border-gray-300">
                </div>

                <input type="text" name="cvc" placeholder="CVC" class="w-80 px-4 py-2 rounded-md border border-gray-300">
            </div>

            <div id="card-errors" role="alert" class="text-red-600 mt-2"></div>
        </div>

        <div class="flex justify-center mt-6">
            <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-6 py-3 rounded-lg transition duration-300">
                Confirmar y pagar
            </button>
        </div>
    </form>
</div>

{{-- Card.js --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/card@2.5.4/dist/card.css">
<script src="https://cdn.jsdelivr.net/npm/card@2.5.4/dist/card.min.js"></script>

<script>
    new Card({
        form: '#pago-formulario',
        container: '#card-wrapper',
        formSelectors: {
            numberInput: 'input[name="number"]',
            expiryInput: 'input[name="exp_month"], input[name="exp_year"]',
            cvcInput: 'input[name="cvc"]',
            nameInput: 'input[name="name"]'
        },
    });

    const metodoPagoSelect = document.getElementById('metodo_pago');
    const tarjetaForm = document.getElementById('formulario-tarjeta');

    metodoPagoSelect.addEventListener('change', function () {
        if (this.value === 'debito' || this.value === 'credito') {
            tarjetaForm.classList.remove('hidden');
        } else {
            tarjetaForm.classList.add('hidden');
        }
    });
</script>
@endsection
