@extends('layouts.sidebar')

@section('content')
<h2 class="text-2xl font-bold text-rose-600 mb-6">Pagar turnos por fecha</h2>

@if(session('success'))
    <div class="p-4 mb-4 bg-green-100 text-green-800 border border-green-300 rounded">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('cliente.pago.tarjeta.procesar') }}" id="pago-grupal">
    @csrf

    @php $totalGeneral = 0; @endphp

    @foreach ($grupos as $index => $grupo)
        @php 
            $fecha = $grupo[0]['fecha']; 
            $totalGrupo = array_sum(array_column($grupo, 'precio'));
            $totalGeneral += $totalGrupo;
        @endphp

        <div class="mb-10 border border-rose-300 p-4 rounded-xl shadow-md grupo-pago bg-white" data-index="{{ $index }}">
            <h3 class="text-lg font-semibold text-rose-700 mb-4">
                Servicios para el {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
            </h3>

            <ul class="mb-4 space-y-2">
                @foreach ($grupo as $turno)
                    <li class="text-sm text-gray-700 border border-pink-200 rounded p-3 bg-rose-50">
                        <strong>Servicio:</strong> {{ $turno['servicio_nombre'] }} —
                        <strong>Hora:</strong> {{ $turno['hora'] }} —
                        <strong>Profesional:</strong> {{ $turno['profesional_nombre'] }} —
                        <strong>Precio:</strong> ${{ number_format($turno['precio'], 2) }}
                    </li>
                    <input type="hidden" name="turnos[{{ $fecha }}][]" value='@json($turno)'>
                @endforeach
            </ul>

            <p class="text-right text-rose-800 font-semibold mb-2">
                Total para esta fecha: ${{ number_format($totalGrupo, 2) }}
            </p>

            <label class="block font-semibold text-rose-600 mb-2">Método de pago:</label>
            <select name="pagos[{{ $fecha }}]" class="pago-select w-full border border-gray-300 rounded px-3 py-2 mb-4" data-index="{{ $index }}" required>
                <option value="">Seleccionar</option>
                <option value="efectivo">Efectivo</option>
                <option value="debito">Débito</option>
                <option value="credito">Crédito</option>
            </select>

            <div class="card-fields hidden border border-rose-300 p-4 rounded-xl mb-4 space-y-3 bg-rose-50">
                <h4 class="text-lg font-semibold text-rose-700 mb-2">Datos de la tarjeta</h4>
                <input type="text" name="number" placeholder="Número de tarjeta" class="card-number-input w-full px-4 py-2 rounded border border-gray-300" minlength="16" maxlength="19" pattern="[0-9\s]{16,19}" title="Ingrese un número de tarjeta válido (16 a 19 dígitos)" />
                <span class="text-red-500 text-xs hidden error-message" id="error-number">El número de tarjeta no es válido.</span>
                
                <input type="text" name="name" placeholder="Nombre del titular" class="card-name-input w-full px-4 py-2 rounded border border-gray-300" />
                <span class="text-red-500 text-xs hidden error-message" id="error-name">El nombre del titular no puede estar vacío.</span>
                
                <div class="flex space-x-2">
                    <input type="text" name="exp_month" placeholder="MM" class="card-month-input w-1/2 px-4 py-2 rounded border border-gray-300" minlength="2" maxlength="2" pattern="(0[1-9]|1[0-2])" title="Mes válido (MM: 01-12)" />
                    <input type="text" name="exp_year" placeholder="AA" class="card-year-input w-1/2 px-4 py-2 rounded border border-gray-300" minlength="2" maxlength="2" pattern="[0-9]{2}" title="Año válido (AA: últimos dos dígitos)" />
                </div>
                <span class="text-red-500 text-xs hidden error-message" id="error-expiry">La fecha de vencimiento no es válida.</span>
                
                <input type="text" name="cvc" placeholder="CVC" class="card-cvc-input w-full px-4 py-2 rounded border border-gray-300" minlength="3" maxlength="4" pattern="[0-9]{3,4}" title="CVC válido (3 o 4 dígitos)" />
                <span class="text-red-500 text-xs hidden error-message" id="error-cvc">El CVC no es válido.</span>
            </div>
        </div>
    @endforeach

    {{-- Resumen final --}}
    <div class="bg-rose-100 border border-rose-300 p-4 rounded-xl mb-6">
        <h4 class="text-lg font-semibold text-rose-700 mb-2">Resumen del pago</h4>
        <p class="text-gray-800 text-base">
            Total general a abonar: <span class="font-bold text-rose-800">${{ number_format($totalGeneral, 2) }}</span>
        </p>
        <p class="text-sm text-gray-600 mt-1">
            * Si abonás con tarjeta de débito 48 hs antes, se aplica el 15% de descuento en la validación del pago.
        </p>
    </div>

    <div class="text-right mt-8">
        <button type="submit" id="submit-button" class="bg-rose-600 hover:bg-rose-700 text-white font-semibold px-6 py-3 rounded transition" disabled>
            Pagar todos los servicios
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('pago-grupal');
    const selects = document.querySelectorAll('.pago-select');
    const submitButton = document.getElementById('submit-button');

    // Función para mostrar/ocultar los campos de la tarjeta
    selects.forEach(select => {
        select.addEventListener('change', function () {
            const index = this.dataset.index;
            const grupo = document.querySelector(`.grupo-pago[data-index="${index}"]`);
            const cardFields = grupo.querySelector('.card-fields');

            if (this.value === 'debito' || this.value === 'credito') {
                cardFields.classList.remove('hidden');
            } else {
                cardFields.classList.add('hidden');
            }
        });
    });

    // Función para validar el número de tarjeta usando el algoritmo de Luhn
    function luhnCheck(cardNumber) {
        let sum = 0;
        let shouldDouble = false;
        for (let i = cardNumber.length - 1; i >= 0; i--) {
            let digit = parseInt(cardNumber.charAt(i));
            if (shouldDouble) {
                digit *= 2;
                if (digit > 9) digit -= 9;
            }
            sum += digit;
            shouldDouble = !shouldDouble;
        }
        return (sum % 10 === 0);
    }

    // Función de validación del formulario
    form.addEventListener('submit', function (event) {
        let formIsValid = true;
        
        // Ocultar todos los mensajes de error al inicio de la validación
        document.querySelectorAll('.error-message').forEach(el => el.classList.add('hidden'));

        document.querySelectorAll('.grupo-pago').forEach(grupo => {
            const selectPago = grupo.querySelector('.pago-select');
            
            // Validar solo si se seleccionó Débito o Crédito
            if (selectPago.value === 'debito' || selectPago.value === 'credito') {
                const cardNumberInput = grupo.querySelector('input[name="number"]');
                const cardNameInput = grupo.querySelector('input[name="name"]');
                const cardMonthInput = grupo.querySelector('input[name="exp_month"]');
                const cardYearInput = grupo.querySelector('input[name="exp_year"]');
                const cardCvcInput = grupo.querySelector('input[name="cvc"]');

                // Validación del número de tarjeta (16 a 19 dígitos)
                const cardNumber = cardNumberInput.value.replace(/\s/g, ''); // Eliminar espacios
                if (!/^\d{16,19}$/.test(cardNumber) || !luhnCheck(cardNumber)) {
                    document.getElementById('error-number').classList.remove('hidden');
                    formIsValid = false;
                }

                // Validación del nombre del titular (no vacío)
                if (cardNameInput.value.trim() === '') {
                    document.getElementById('error-name').classList.remove('hidden');
                    formIsValid = false;
                }

                // Validación de la fecha de vencimiento (mes y año)
                const currentYear = new Date().getFullYear() % 100; // Obtener los dos últimos dígitos del año
                const currentMonth = new Date().getMonth() + 1; // Mes actual (1-12)
                const expMonth = parseInt(cardMonthInput.value, 10);
                const expYear = parseInt(cardYearInput.value, 10);
                
                if (
                    !/^(0[1-9]|1[0-2])$/.test(cardMonthInput.value) ||
                    !/^\d{2}$/.test(cardYearInput.value) ||
                    (expYear < currentYear || (expYear === currentYear && expMonth < currentMonth))
                ) {
                    document.getElementById('error-expiry').classList.remove('hidden');
                    formIsValid = false;
                }
                
                // Validación del CVC (3 o 4 dígitos)
                const cvc = cardCvcInput.value;
                if (!/^\d{3,4}$/.test(cvc)) {
                    document.getElementById('error-cvc').classList.remove('hidden');
                    formIsValid = false;
                }
            }
        });

        // Si alguna validación falla, evitar que el formulario se envíe
        if (!formIsValid) {
            event.preventDefault();
            alert('Por favor, corrige los errores en los datos de la tarjeta.');
        }
    });

    // Habilitar o deshabilitar el botón de enviar según la validez del formulario
    form.addEventListener('input', () => {
        let allValid = true;
        document.querySelectorAll('.grupo-pago').forEach(grupo => {
            const selectPago = grupo.querySelector('.pago-select');
            if (selectPago.value === 'debito' || selectPago.value === 'credito') {
                const cardNumber = grupo.querySelector('input[name="number"]').value.replace(/\s/g, '');
                const cardName = grupo.querySelector('input[name="name"]').value.trim();
                const expMonth = grupo.querySelector('input[name="exp_month"]').value;
                const expYear = grupo.querySelector('input[name="exp_year"]').value;
                const cvc = grupo.querySelector('input[name="cvc"]').value;

                // Verifica que los campos estén completos y válidos
                if (!/^\d{16,19}$/.test(cardNumber) || !cardName || !/^\d{2}$/.test(expMonth) || !/^\d{2}$/.test(expYear) || !/^\d{3,4}$/.test(cvc)) {
                    allValid = false;
                }
            }
        });
        submitButton.disabled = !allValid;
    });
});
</script>
@endsection
