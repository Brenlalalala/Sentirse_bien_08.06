@extends('layouts.sidebar')

@section('content')
<div class="max-w-5xl mx-auto bg-white shadow rounded p-6">
    <h2 class="text-2xl font-bold text-pink-600 mb-6">Clientes registrados</h2>

    @if($clientes->isEmpty())
        <p class="text-gray-600">No hay clientes registrados.</p>
    @else
        <table class="w-full table-auto border-collapse">
            <thead class="bg-pink-100 text-pink-900 font-semibold">
                <tr>
                    <th class="p-2 border">Nombre</th>
                    <th class="p-2 border">Email</th>
                    <th class="p-2 border">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $cliente)
                    <tr class="text-center">
                        <td class="p-2 border">{{ $cliente->name }}</td>
                        <td class="p-2 border">{{ $cliente->email }}</td>
                        <td class="p-2 border">
                            <a href="{{ route('admin.historial.cliente', $cliente->id) }}"
                               class="text-blue-600 hover:underline">
                                Ver Historial
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
