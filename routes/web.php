<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiciosController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\ReservaController;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\Cliente;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ClienteServiciosController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TurnosPorDiaController;


// Rutas públicas
Route::get('/', fn() => view('home'))->name('home');
Route::get('/conocenos', fn() => view('conocenos'))->name('conocenos');
Route::get('/servicios', [ServiciosController::class, 'index'])->name('servicios.index');
Route::get('/servicios/{servicio}', [ServiciosController::class, 'show'])->name('servicios.show');
Route::get('/contacto', fn() => view('contacto'))->name('contacto');
Route::post('/contacto', [ContactoController::class, 'enviar'])->name('contacto.enviar');
Route::post('/reservar', [ReservaController::class, 'store'])->name('reservar');

// 👉 Ruta del perfil del cliente
    Route::get('/cliente/perfil', [ClienteController::class, 'perfil'])->name('cliente.perfil');

Route::get('/dashboard', fn() => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Rutas para admin- servicios
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/servicios', [ServiciosController::class, 'adminIndex'])->name('admin.servicios.index');
    Route::post('/servicios', [ServiciosController::class, 'guardarServicio'])->name('admin.servicios.store');
    Route::delete('/servicios/{servicio}', [ServiciosController::class, 'destroy'])->name('admin.servicios.destroy');
    // En otro paso agregaremos update/edit

    //ruta para turnos 
    Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/turnos', [TurnoController::class, 'index'])->name('admin.turnos.index');
});

//ruta para controlar usuarios 
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('admin.usuarios.index');
    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('admin.usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('admin.usuarios.store');
    Route::get('/usuarios/{user}/editar', [UsuarioController::class, 'edit'])->name('admin.usuarios.edit');
    Route::put('/usuarios/{user}', [UsuarioController::class, 'update'])->name('admin.usuarios.update');
    Route::delete('/usuarios/{user}', [UsuarioController::class, 'destroy'])->name('admin.usuarios.destroy');
});

//ruta para ver turnos por dia
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('turnos-dia', [\App\Http\Controllers\TurnosPorDiaController::class, 'index'])->name('turnos.dia');
});


});



require __DIR__.'/auth.php';

