<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = [
        'cliente_id',
        'servicio',
        'fecha',
        'hora',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
