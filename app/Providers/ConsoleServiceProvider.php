<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\LimpiarServiciosDuplicados;

class ConsoleServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Registrar comandos
        $this->commands([
            LimpiarServiciosDuplicados::class,
        ]);
    }

    public function boot()
    {
        // Si quieres programar tareas con scheduler
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);

            // Por ejemplo, cada día a las 2 AM
            $schedule->command('servicios:limpiar-duplicados')->dailyAt('02:00');
        });
    }
}
