@extends('layouts.sidebar')

@section('content')
    <div class="max-w-6xl mx-auto p-6 bg-white shadow rounded">
        <h2 class="text-2xl font-bold text-pink-600 mb-6">Historial de Servicios de {{ $user->name }}</h2>

        @if ($turnosRealizados->isEmpty())
            <p class="text-gray-600">Este cliente no ha realizado ningún servicio aún.</p>
        @else
            <table class="w-full table-auto border-collapse">
                <thead class="bg-pink-200 text-pink-900 font-semibold">
                    <tr>
                        <th class="p-2 border">Servicio</th>
                        <th class="p-2 border">Fecha</th>
                        <th class="p-2 border">Hora</th>
                        <th class="p-2 border">Monto</th>
                        <th class="p-2 border">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($turnosRealizados as $turno)
                        <tr class="text-center">
                            <td class="p-2 border">{{ $turno->servicio->nombre }}</td>
                            <td class="p-2 border">{{ \Carbon\Carbon::parse($turno->fecha)->format('d/m/Y') }}</td>
                            <td class="p-2 border">{{ \Carbon\Carbon::parse($turno->hora)->format('H:i') }}</td>
                            <td class="p-2 border">${{ number_format($turno->monto ?? 0, 2, ',', '.') }}</td>
                            <td class="p-2 border capitalize">{{ $turno->estado ?? 'realizado' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
