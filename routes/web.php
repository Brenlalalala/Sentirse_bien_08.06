<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiciosController;
use App\Http\Controllers\ContactoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ClienteTurnoController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TurnosPorDiaController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ProfesionalController;
use App\Http\Controllers\ClienteHistorialController;

// Rutas públicas
Route::get('/', fn() => view('home'))->name('home');
Route::get('/conocenos', fn() => view('conocenos'))->name('conocenos');
Route::get('/servicios', [ServiciosController::class, 'index'])->name('servicios.index');
Route::get('/servicios/{servicio}', [ServiciosController::class, 'show'])->name('servicios.show');
Route::get('/contacto', fn() => view('contacto'))->name('contacto');
Route::post('/contacto', [ContactoController::class, 'enviar'])->name('contacto.enviar');
Route::middleware('auth')->post('/reservar', [ReservaController::class, 'store'])->name('reservar');

// Perfil del cliente (ruta pública si querés, o con auth según necesidad)
Route::get('/cliente/perfil', [ClienteController::class, 'perfil'])->name('cliente.perfil');

// Dashboard protegido con autenticación y verificación
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Rutas para editar perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas para ADMIN (prefijo /admin, solo rol admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Servicios
    Route::get('/servicios', [ServiciosController::class, 'adminIndex'])->name('servicios.index');
    
    Route::post('/servicios', [ServiciosController::class, 'guardarServicio'])->name('servicios.store');
    Route::delete('/servicios/{servicio}', [ServiciosController::class, 'destroy'])->name('servicios.destroy');
    Route::get('/servicios/{servicio}/edit', [ServiciosController::class, 'edit'])->name('servicios.edit');
    Route::put('/servicios/{servicio}', [ServiciosController::class, 'update'])->name('servicios.update');
    Route::get('/servicios/create', [ServiciosController::class, 'create'])->name('servicios.create');

    
    // Turnos
    Route::get('/turnos', [TurnoController::class, 'index'])->name('turnos.index');

    // Usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{user}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{user}', [UsuarioController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{user}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');

    // Turnos por día
    Route::get('/turnos-dia', [TurnosPorDiaController::class, 'index'])->name('turnos.dia');
});

// Rutas para CLIENTE (sin prefijo, solo rol cliente)
Route::middleware(['auth', 'role:cliente'])->group(function () {
    Route::get('/cliente/reservar-turno', [ClienteTurnoController::class, 'create'])->name('cliente.reservar-turno');
    Route::post('/cliente/reservar-turno', [ClienteTurnoController::class, 'store'])->name('cliente.turnos.store');
    Route::get('/cliente/servicios', [ServiciosController::class, 'index'])
    ->name('cliente.servicios.index');

  
   // Route::post('/cliente/turnos', [ClienteTurnoController::class, 'store'])->name('cliente.turnos.store');

Route::get('/cliente/mis-servicios', [ClienteTurnoController::class, 'misServicios'])->name('cliente.mis-servicios');
Route::delete('/cliente/turnos/{turno}/cancelar', [ClienteTurnoController::class, 'cancelar'])->name('cliente.turno.cancelar');
Route::get('/cliente/historial', [ClienteTurnoController::class, 'historial'])->name('cliente.historial');

});


// Rutas para PROFESIONAL (sin prefijo, solo rol profesional)
Route::middleware(['auth'])->group(function () {
    Route::get('/profesional/turnos-dia', [ProfesionalController::class, 'turnosDelDia'])->name('profesional.turnos.dia');
    Route::post('/profesional/agregar-historial', [ProfesionalController::class, 'agregarHistorial'])->name('profesional.historial.agregar');
    Route::get('/profesional/historial/{id}', [ProfesionalController::class, 'verHistorial'])->name('profesional.historial.ver');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/cliente/historial', [ClienteTurnoController::class, 'historial'])->name('cliente.historial');
});



//chatbot
Route::post('/chatbot', [ChatbotController::class, 'responder']);

require __DIR__.'/auth.php';