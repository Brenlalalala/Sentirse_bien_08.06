<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historial extends Model
{
    protected $table = 'historial'; // Aquí le decimos que la tabla es 'historial'

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function profesional()
    {
        return $this->belongsTo(User::class, 'profesional_id');
    }
}
