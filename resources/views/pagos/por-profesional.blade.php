@extends('layouts.sidebar')

@section('content')
<h2 class="text-2xl font-bold mb-6">Pagos por Profesional</h2>

<form method="GET" class="mb-6">
    <label>Desde: <input type="date" name="desde" value="{{ $desde }}"></label>
    <label>Hasta: <input type="date" name="hasta" value="{{ $hasta }}"></label>
    <button type="submit" class="ml-2 px-3 py-1 bg-rose-600 text-white rounded">Filtrar</button>
</form>

<table class="w-full border-collapse border border-gray-300">
    <thead>
        <tr class="bg-rose-200">
            <th class="border border-gray-300 px-4 py-2">Profesional</th>
            <th class="border border-gray-300 px-4 py-2">Total Pagado</th>
        </tr>
    </thead>
    <tbody>
        @forelse($resultados as $fila)
            <tr>
                <td class="border border-gray-300 px-4 py-2">{{ $fila->profesional }}</td>
                <td class="border border-gray-300 px-4 py-2">${{ number_format($fila->total, 2) }}</td>
            </tr>
        @empty
            <tr><td colspan="2" class="text-center py-4">No hay registros</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
