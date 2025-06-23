<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class HorarioProfesional extends Model
{
    use HasFactory;

    protected $table = 'horarios_profesionales'; 

    protected $fillable = [
        'user_id',
        'dia',
        'hora_inicio',
        'hora_fin',
    ];

    public function profesional()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
