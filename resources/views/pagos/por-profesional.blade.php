@extends('layouts.sidebar')

@section('content')
<h2 class="text-3xl font-extrabold text-rose-700 mb-6">Pagos por Profesional</h2>

{{-- Formulario para filtrar y exportar --}}
<form id="filtro-form" method="GET" action="{{ route('pagos.por-profesional') }}" class="mb-8 flex flex-wrap items-end gap-4 text-base">
    <label>
        Desde:
        <input type="date" name="desde" value="{{ $desde }}" required class="border rounded px-2 py-1">
    </label>
    <label>
        Hasta:
        <input type="date" name="hasta" value="{{ $hasta }}" required class="border rounded px-2 py-1">
    </label>

    <button type="submit" class="px-4 py-2 bg-rose-600 text-white rounded shadow hover:bg-rose-700 transition">
        Filtrar
    </button>

    <button type="button"
        onclick="exportarPDF()"
        class="px-4 py-2 bg-pink-500 text-white rounded shadow hover:bg-pink-600 transition">
        Exportar Pagos
    </button>
</form>

{{-- Tabla de resultados --}}
<div class="bg-white rounded-lg shadow border border-gray-200 max-w-4xl p-4 overflow-x-auto">
    <table class="w-full text-lg border-collapse">
        <thead>
            <tr class="bg-rose-200 text-gray-800 uppercase tracking-wide">
                <th class="border border-gray-300 px-4 py-3 text-left">👥 Profesional</th>
                <th class="border border-gray-300 px-4 py-3 text-left">💰 Total Pagado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($resultados as $fila)
                <tr class="hover:bg-rose-50">
                    <td class="border-b border-gray-200 px-4 py-3">{{ $fila->profesional }}</td>
                    <td class="border-b border-gray-200 px-4 py-3">${{ number_format($fila->total, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center py-6 text-gray-500">No hay registros</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Script para exportar PDF --}}
<script>
function exportarPDF() {
    const desde = document.getElementById('desde').value;
    const hasta = document.getElementById('hasta').value;

    if (desde && hasta) {
        const url = `{{ route('pagos.exportar.profesionales') }}?desde=${desde}&hasta=${hasta}`;
        window.open(url, '_blank');
    } else {
        alert('Seleccioná ambas fechas para exportar.');
    }
}
</script>
@endsection
