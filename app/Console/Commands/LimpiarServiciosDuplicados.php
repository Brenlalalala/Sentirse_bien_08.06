<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Servicio;
use Illuminate\Support\Facades\DB;

class LimpiarServiciosDuplicados extends Command
{
    protected $signature = 'servicios:limpiar-duplicados';
    protected $description = 'Elimina servicios duplicados por nombre, dejando solo uno';

    public function handle()
    {
        $duplicados = Servicio::select('nombre')
            ->groupBy('nombre')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('nombre');

        $totalEliminados = 0;

        foreach ($duplicados as $nombre) {
            $ids = Servicio::where('nombre', $nombre)
                ->orderBy('id')
                ->pluck('id');

            // Mantener solo el primer ID
            $idsParaEliminar = $ids->slice(1);

            $eliminados = Servicio::whereIn('id', $idsParaEliminar)->delete();
            $totalEliminados += $eliminados;

            $this->info("✔️ Eliminados {$eliminados} duplicados del servicio: {$nombre}");
        }

        if ($totalEliminados === 0) {
            $this->info("No se encontraron servicios duplicados.");
        } else {
            $this->info("✅ Total eliminados: $totalEliminados");
        }
    }
}
