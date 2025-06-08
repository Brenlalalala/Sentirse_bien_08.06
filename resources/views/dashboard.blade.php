@extends('layouts.sidebar')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Bienvenida al sistema, {{ Auth::user()->name }}</h1>
    <p>Desde aquí podés comenzar a usar tu panel según tu perfil.</p>
@endsection