<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    protected $table = 'historial';

    // Permitir asignación masiva de estos campos
    protected $fillable = [
        'user_id',
        'profesional_id',
        'servicio_id',
        'detalle',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function profesional()
    {
        return $this->belongsTo(User::class, 'profesional_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }


}
