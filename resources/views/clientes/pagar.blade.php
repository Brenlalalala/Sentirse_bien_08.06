@extends('layouts.sidebar')

@section('content')
<!-- Mantén estilos y scripts que usabas para Card.js si quieres la animación visual -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/card@2.5.4/dist/card.css">
<script src="https://cdn.jsdelivr.net/npm/card@2.5.4/dist/card.min.js"></script>

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

<style>
/* Tus estilos personalizados para inputs, botones, etc. */
</style>

<div class="form-container">
    <h2 class="text-xl font-bold mb-4">Pagar con tarjeta</h2>

    <form id="payment-form" method="POST" action="{{ route('pago.procesar') }}">
        @csrf
        <div id="card-wrapper" class="mb-4"></div>

        <div class="flex flex-col items-center space-y-3"> 
            <input type="text" name="number" placeholder="Número de tarjeta" required
                class="w-80 px-4 py-2 rounded-md text-gray border border-gray-300">

            <input type="text" name="name" placeholder="Nombre del titular" required
                class="w-80 px-4 py-2 rounded-md text-gray border border-gray-300">

            <div class="flex space-x-2">
                <input type="text" name="exp_month" placeholder="MM" required
                    class="w-36 px-4 py-2 rounded-md text-gray border border-gray-300">
                <input type="text" name="exp_year" placeholder="AA" required
                    class="w-36 px-4 py-2 rounded-md text-gray border border-gray-300">
            </div>

            <input type="text" name="cvc" placeholder="CVC" required
                class="w-80 px-4 py-2 rounded-md text-gray border border-gray-300">
        </div>

        <div id="card-errors" role="alert" class="text-red-600 mt-2"></div>

        <div id="loading" style="display:none; text-align:center; margin-top:10px;">
            <p style="color:#93c5fd;">Procesando pago...</p>
        </div>

        <div id="success-message" class="mt-6 p-4 text-green-800 bg-green-100 border border-green-300 rounded-lg" style="display: none;">
            ¡Pago exitoso! Gracias por elegirnos 💖
        </div>

        <div class="flex justify-center mt-6">
            <button type="submit" id="submit-button" class="bg-pink-500 hover:bg-pink-600 text-white font-semibold px-6 py-3 rounded-lg transition duration-300">
                Pagar
            </button>
        </div>
    </form>
</div>

<script>
new Card({
    form: '#payment-form',
    container: '#card-wrapper',
    formSelectors: {
        numberInput: 'input[name="number"]',
        expiryInput: 'input[name="exp_month"], input[name="exp_year"]',
        cvcInput: 'input[name="cvc"]',
        nameInput: 'input[name="name"]'
    },
    placeholders: {
        number: '•••• •••• •••• ••••',
        name: 'Nombre del titular',
        cvc: '•••',
        expiry: '•• / ••'
    }
});

const form = document.getElementById('payment-form');
const loading = document.getElementById('loading');
const errorDiv = document.getElementById('card-errors');
const successMessage = document.getElementById('success-message');
const submitButton = document.getElementById('submit-button');

form.addEventListener('submit', function(e) {
    e.preventDefault();
    errorDiv.textContent = '';
    loading.style.display = 'block';
    submitButton.disabled = true;

    // Simulamos un delay para parecer real
    setTimeout(() => {
        loading.style.display = 'none';

        // Aquí enviar el formulario realmente al backend (si quieres hacerlo por JS, descomenta)
        // form.submit();

        // O simulamos que fue exitoso y mostramos mensaje:
        form.style.display = 'none';
        successMessage.style.display = 'block';

        // Redirige luego de 2.5 segundos
        setTimeout(() => {
            window.location.href = "{{ route('cliente.mis-servicios') }}";
        }, 2500);

    }, 1500); // 1.5 segundos de "procesando"
});
</script>
@endsection
