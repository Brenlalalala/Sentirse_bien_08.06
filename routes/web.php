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
use App\Http\Controllers\ClientePagoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HorarioProfesionalController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\PagoController;

use App\Http\Controllers\UserController;
use Database\Seeders\ClienteSeeder;

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
    Route::get('/turnos/{id}/edit', [TurnoController::class, 'edit'])->name('turnos.edit');
    Route::put('/turnos/{id}', [TurnoController::class, 'update'])->name('turnos.update');
    Route::delete('/turnos/{id}', [TurnoController::class, 'destroy'])->name('turnos.cancelar');


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
// Route::middleware(['auth'])->group(function () {
//     Route::get('/profesional/turnos-dia', [ProfesionalController::class, 'turnosDelDia'])->name('profesional.turnos.dia');
//     Route::post('/profesional/agregar-historial', [ProfesionalController::class, 'agregarHistorial'])->name('profesional.historial.agregar');
 //Route::get('/profesional/historial/{id}', [ProfesionalController::class, 'verHistorial'])->name('profesional.historial.ver');
// });
   Route::middleware(['auth', 'role:profesional'])->group(function () {
    Route::get('/profesional/turnos', [ProfesionalController::class, 'verTurnos'])->name('profesional.turnos');
    Route::get('/profesional/turnos/pdf', [ProfesionalController::class, 'exportarTurnosPdf'])->name('profesional.turnos.pdf');
    Route::get('/profesional/historial', [ProfesionalController::class, 'verHistorial'])->name('profesional.historial.ver');
    Route::post('/profesional/historial/agregar', [ProfesionalController::class, 'agregarHistorial'])->name('profesional.historial.agregar');

});

Route::middleware(['auth'])->group(function () {
    Route::get('/cliente/historial', [ClienteTurnoController::class, 'historial'])->name('cliente.historial');
});


//chatbot
Route::post('/chatbot', [ChatbotController::class, 'responder']);

//Ruta para boton volver desde mis servicios a dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Ruta para imprimir turnos por dia ADMIN
Route::get('/admin/turnos/imprimir', [TurnosPorDiaController::class, 'imprimir'])->name('admin.turnos.imprimir');


Route::get('/admin/clientes', [UserController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.clientes');

Route::get('/admin/historial-cliente/{user}', [TurnoController::class, 'verHistorialCliente'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.historial.cliente');

    //disponibilidad horaria de profesionales

Route::get('/horarios-disponibles', [TurnoController::class, 'horariosDisponibles'])->name('horarios.disponibles');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::resource('/horarios', HorarioProfesionalController::class)->names('horarios');
});

// //pago
 Route::get('/clientes/pagar', [ClientePagoController::class, 'mostrarFormulario'])->name('pago.formulario');
 Route::post('/clientes/pagar', [ClientePagoController::class, 'procesarPago'])->name('pago.procesar');

// Mostrar formulario de pago del segundo grupo (si se usa Sesión con 'grupo2')
Route::get('/cliente/pagar/segundo-grupo', [ClientePagoController::class, 'mostrarSegundoGrupo'])->name('cliente.pagar.segundo-grupo');

// Procesar el pago del segundo grupo
Route::post('/cliente/pagar/segundo-grupo', [ClientePagoController::class, 'procesarPagoSegundoGrupo'])->name('cliente.pagar.segundo-grupo.procesar');


// Procesar reservas
Route::post('/cliente/turnos/store', [ClienteTurnoController::class, 'store'])->name('cliente.turnos.store');

// Simulación de pago con tarjeta
Route::get('/cliente/pago-tarjeta', [ClienteTurnoController::class, 'vistaPagoTarjeta'])->name('cliente.pago.tarjeta');

// Simular procesamiento (solo botón "Pagar")
Route::post('/cliente/pago-tarjeta/procesar', [ClienteTurnoController::class, 'procesarPagoTarjeta'])->name('cliente.pago.tarjeta.procesar');



//listado de pagos
Route::middleware(['auth'])->group(function () {
 Route::get('/pagos/por-servicio', [PagoController::class, 'porServicio'])->name('pagos.por-servicio');
 Route::get('/pagos/por-profesional', [PagoController::class, 'porProfesional'])->name('pagos.por-profesional');
Route::get('/cliente/mis-pagos', [PagoController::class, 'misPagos'])->name('cliente.pagos.mis-pagos');
Route::get('/pagos/exportar/profesionales', [PagoController::class, 'exportarPagosPorProfesional'])->name('pagos.exportar.profesionales');
Route::get('/pagos/exportar/servicios', [PagoController::class, 'exportarPagosPorServicio'])->name('pagos.exportar.servicios');

});

// Ruta para generar y descargar el comprobante de pago
    Route::get('/mis-servicios/comprobante/{pago}', [App\Http\Controllers\ClientePagoController::class, 'descargarComprobante'])->name('cliente.descargar.comprobante');


require __DIR__.'/auth.php';