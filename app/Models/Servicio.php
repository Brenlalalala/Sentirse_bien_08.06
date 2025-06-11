<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Servicio extends Model
{

        use HasFactory;

    public function turnos()
    {
        return $this->hasMany(Turno::class);
    }
    protected $fillable = [
        'categoria',
        'subcategoria',
        'nombre',
        'descripcion',
        'precio',
        'imagen'
    ];
}



