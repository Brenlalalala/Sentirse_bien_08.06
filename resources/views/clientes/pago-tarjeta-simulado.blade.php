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
                <input type="text" name="number" placeholder="Número de tarjeta" class="w-full px-4 py-2 rounded border border-gray-300" />
                <input type="text" name="name" placeholder="Nombre del titular" class="w-full px-4 py-2 rounded border border-gray-300" />
                <div class="flex space-x-2">
                    <input type="text" name="exp_month" placeholder="MM" class="w-1/2 px-4 py-2 rounded border border-gray-300" />
                    <input type="text" name="exp_year" placeholder="AA" class="w-1/2 px-4 py-2 rounded border border-gray-300" />
                </div>
                <input type="text" name="cvc" placeholder="CVC" class="w-full px-4 py-2 rounded border border-gray-300" />
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
        <button type="submit"
            class="bg-rose-600 hover:bg-rose-700 text-white font-semibold px-6 py-3 rounded transition">
            Pagar todos los servicios
        </button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const selects = document.querySelectorAll('.pago-select');

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
});
</script>
@endsection
