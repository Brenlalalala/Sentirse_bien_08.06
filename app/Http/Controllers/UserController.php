<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
public function index()
{
    $clientes = User::role('cliente')->get(); // Si usás Spatie Roles
    return view('admin.clientes.index', compact('clientes'));
}

}
