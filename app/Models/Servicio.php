<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = [
        'categoria',
        'subcategoria',
        'nombre',
        'descripcion',
        'precio',
        'imagen'
    ];
}



