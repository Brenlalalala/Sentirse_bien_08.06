@extends('layouts.sidebar')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">

    <h2 class="text-2xl font-bold text-pink-600 mb-6">Pagar servicios restantes</h2>

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

    <form id="pago-formulario" method="POST" action="{{ route('pago.procesar.grupo2') }}">
        @csrf

        {{-- Lista de servicios a pagar --}}
        <ul class="mb-6 space-y-4">
            @foreach ($grupo2 as $turno)
                <li class="border border-pink-300 rounded p-4">
                    <p><strong>Servicio:</strong> {{ \App\Models\Servicio::find($turno['servicio_id'])->nombre ?? 'Servicio' }}</p>
                    <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($turno['fecha'])->format('d/m/Y') }}</p>
                    <p><strong>Hora:</strong> {{ $turno['hora'] }}</p>
                    <p><strong>Profesional:</strong> {{ \App\Models\User::find($turno['profesional_id'])->name ?? 'Profesional' }}</p>
                    <input type="hidden" name="turnos[]" value="{{ json_encode($turno) }}">
                </li>
            @endforeach
        </ul>

        {{-- Selector de método de pago --}}
        <label for="metodo_pago" class="block font-semibold text-pink-700 mb-2">Seleccioná método de pago:</label>
        <select name="metodo_pago" id="metodo_pago" required
            class="w-full border border-pink-300 rounded px-4 py-2 mb-6 focus:outline-none focus:ring-2 focus:ring-pink-500">
            <option value="">-- Seleccioná --</option>
            <option value="efectivo" {{ $metodo === 'efectivo' ? 'selected' : '' }}>Efectivo</option>
            <option value="debito" {{ $metodo === 'debito' ? 'selected' : '' }}>Tarjeta Débito</option>
            <option value="credito" {{ $metodo === 'credito' ? 'selected' : '' }}>Tarjeta Crédito</option>
        </select>

        {{-- Campos para tarjeta --}}
        <div id="card-section" class="hidden mt-6">
            <h3 class="text-xl font-semibold text-pink-600 mb-4">Datos de la tarjeta</h3>

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
        </div>

        <div id="card-errors" class="text-red-600 mt-2"></div>
        <div id="loading" class="hidden text-pink-500 mt-4">Procesando pago...</div>

        <div id="success-message" class="hidden mt-6 p-4 text-green-800 bg-green-100 border border-green-300 rounded">
            ¡Pago exitoso! Gracias por elegirnos 💖
        </div>

        <div class="flex justify-center mt-6">
            <button type="submit" id="submit-button" class="bg-pink-600 hover:bg-pink-700 text-white font-semibold px-6 py-3 rounded transition">
                Pagar y reservar
            </button>
        </div>
    </form>
</div>

{{-- Scripts de Card.js --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/card@2.5.4/dist/card.css">
<script src="https://cdn.jsdelivr.net/npm/card@2.5.4/dist/card.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const metodoPago = document.getElementById('metodo_pago');
    const cardSection = document.getElementById('card-section');
    const form = document.getElementById('pago-formulario');
    const loading = document.getElementById('loading');
    const success = document.getElementById('success-message');
    const errors = document.getElementById('card-errors');
    const submitBtn = document.getElementById('submit-button');

    if (metodoPago.value === 'debito' || metodoPago.value === 'credito') {
        cardSection.classList.remove('hidden');
    }

    metodoPago.addEventListener('change', () => {
        if (metodoPago.value === 'debito' || metodoPago.value === 'credito') {
            cardSection.classList.remove('hidden');
        } else {
            cardSection.classList.add('hidden');
        }
    });

    new Card({
        form: '#pago-formulario',
        container: '#card-wrapper',
        formSelectors: {
            numberInput: 'input[name="number"]',
            expiryInput: 'input[name="exp_month"], input[name="exp_year"]',
            cvcInput: 'input[name="cvc"]',
            nameInput: 'input[name="name"]'
        }
    });

    form.addEventListener('submit', function(e) {
        if (metodoPago.value === 'credito' || metodoPago.value === 'debito') {
            e.preventDefault();
            errors.textContent = '';
            loading.classList.remove('hidden');
            submitBtn.disabled = true;

            setTimeout(() => {
                loading.classList.add('hidden');
                form.style.display = 'none';
                success.classList.remove('hidden');

                setTimeout(() => {
                    window.location.href = "{{ route('cliente.mis-servicios') }}";
                }, 2500);
            }, 1500);
        }
    });
});
</script>
@endsection
