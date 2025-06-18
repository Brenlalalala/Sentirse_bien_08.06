@extends('layouts.sidebar')

@section('content')
    <div class="p-6 bg-white rounded shadow-md">
        <h1 class="text-3xl font-bold text-pink-700 mb-4">
            ¡Bienvenid@ {{ Auth::user()->name }}!
        </h1>

        <p class="text-gray-700 text-lg mb-4">
            Este es tu panel de 
            <strong>
                @role('admin')
                    administración
                @elserole('profesional')
                    profesional
                @else
                    cliente
                @endrole
            </strong>.
        </p>

        <p class="text-gray-600">
            Desde aquí podés acceder a todas tus funciones principales. Usá el menú lateral para navegar.
        </p>
    </div>

    
@endsection
