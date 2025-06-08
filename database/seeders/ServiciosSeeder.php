<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServiciosSeeder extends Seeder
{
    public function run()
    {
        $servicios = [
            // Individuales - Masajes
            ['categoria' => 'individuales', 'subcategoria' => 'Masajes', 'nombre' => 'Anti-stress', 'descripcion' => 'Relaja cuerpo y mente, alivia tensiones.', 'precio' => 8000],
            ['categoria' => 'individuales', 'subcategoria' => 'Masajes', 'nombre' => 'Descontracturantes', 'descripcion' => 'Alivio profundo de contracturas musculares.', 'precio' => 8500],
            ['categoria' => 'individuales', 'subcategoria' => 'Masajes', 'nombre' => 'Masajes con piedras calientes', 'descripcion' => 'Terapia con calor para armonizar energías.', 'precio' => 9000],
            ['categoria' => 'individuales', 'subcategoria' => 'Masajes', 'nombre' => 'Circulatorios', 'descripcion' => 'Estimula circulación y reduce retención de líquidos.', 'precio' => 8000],
            
            // Belleza
            ['categoria' => 'individuales', 'subcategoria' => 'Belleza', 'nombre' => 'Lifting de pestaña', 'descripcion' => 'Realza tus pestañas sin extensiones.', 'precio' => 7000],
            ['categoria' => 'individuales', 'subcategoria' => 'Belleza', 'nombre' => 'Depilación facial', 'descripcion' => 'Piel suave, sin vello indeseado.', 'precio' => 4500],
            ['categoria' => 'individuales', 'subcategoria' => 'Belleza', 'nombre' => 'Belleza de manos y pies', 'descripcion' => 'Manicure + Pedicure profesional.', 'precio' => 6000],
            
            // Tratamientos Faciales
            ['categoria' => 'individuales', 'subcategoria' => 'Tratamientos Faciales', 'nombre' => 'Punta de Diamante', 'descripcion' => 'Microexfoliación profunda para una piel renovada.', 'precio' => 10000],
            ['categoria' => 'individuales', 'subcategoria' => 'Tratamientos Faciales', 'nombre' => 'Limpieza profunda + Hidratación', 'descripcion' => 'Piel fresca, limpia e hidratada.', 'precio' => 9000],
            ['categoria' => 'individuales', 'subcategoria' => 'Tratamientos Faciales', 'nombre' => 'Crio frecuencia facial', 'descripcion' => 'Produce el SHOCK TÉRMICO logrando resultados instantáneos de efecto lifting.', 'precio' => 12000],

            // Tratamientos Corporales
            ['categoria' => 'individuales', 'subcategoria' => 'Tratamientos Corporales', 'nombre' => 'VelaSlim', 'descripcion' => 'Reducción de la circunferencia corporal y la celulitis.', 'precio' => 14000],
            ['categoria' => 'individuales', 'subcategoria' => 'Tratamientos Corporales', 'nombre' => 'DermoHealth', 'descripcion' => 'Moviliza tejidos y estimula microcirculación, generando drenaje linfático.', 'precio' => 13000],
            ['categoria' => 'individuales', 'subcategoria' => 'Tratamientos Corporales', 'nombre' => 'Criofrecuencia', 'descripcion' => 'Produce un efecto de lifting instantáneo.', 'precio' => 13500],
            ['categoria' => 'individuales', 'subcategoria' => 'Tratamientos Corporales', 'nombre' => 'Ultracavitación', 'descripcion' => 'Técnica reductora para adiposidad localizada.', 'precio' => 12500],

            // Grupales
            ['categoria' => 'grupales', 'subcategoria' => 'Hidromasajes', 'nombre' => 'Hidromasajes', 'descripcion' => 'Sesiones compartidas para relajar y conectar.', 'precio' => 15000],
            ['categoria' => 'grupales', 'subcategoria' => 'Yoga', 'nombre' => 'Yoga', 'descripcion' =>  'Clases para armonizar cuerpo y mente.', 'precio' => 8000],
        ];

        foreach ($servicios as $s) {
            Servicio::create($s);
        }
    }
}
