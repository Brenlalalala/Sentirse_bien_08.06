<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    public function perfil()
    {
        $cliente = Auth::user(); // ya estás autenticado
        return view('cliente.perfil', compact('cliente'));
    }



// Función para operaciones CRUD de clientes

public function editar()
{
    $cliente = auth()->user(); // obtiene al cliente logueado
    return view('cliente.editar', compact('cliente'));
}

public function actualizar(Request $request)
{
    $cliente = auth()->user();

    $request->validate([
        'name' => 'required|string|max:255',
        'telefono' => 'nullable|string|max:20',
        'email' => 'required|email|unique:users,email,' . $cliente->id,

    ]);

    $cliente->update($request->only('name', 'telefono', 'email'));

    return redirect('/perfil')->with('success', 'Perfil actualizado correctamente.');
}
}
