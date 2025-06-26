@extends('layouts.sidebar')

@section('content')
<h2 class="text-2xl font-bold text-rose-600 mb-6">Mis pagos</h2>

@if(session('success'))
    <div class="p-4 mb-4 bg-green-100 text-green-800 border border-green-300 rounded">
        {{ session('success') }}
    </div>
@endif

@if($pagos->isEmpty())
    <p>No se encontraron pagos registrados.</p>
@else
    <table class="w-full table-auto border-collapse border border-gray-300">
        <thead>
            <tr class="bg-rose-100">
              
                <th class="border border-gray-300 px-4 py-2 text-left">Importe</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Descuento</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Método de pago</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Fecha y hora de pago</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pagos as $pago)
                <tr>
                  
                    <td class="border border-gray-300 px-4 py-2">${{ number_format($pago->monto, 2) }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $pago->descuento }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ ucfirst($pago->forma_pago) }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y - H:i') }}</td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $pagos->links() }} {{-- Paginación si usás paginate() --}}
    </div>
@endif

@endsection
