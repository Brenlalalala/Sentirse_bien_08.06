<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'servicio_id',
        'fecha',
        'hora',
        'estado',
        'profesional_id',
        'pagado',
        'monto',
        'medio_pago',
        'comprobante_pdf',
        'notas',
        'pago_id',
    ];

    
    protected $casts = [
        'fecha' => 'date',
        'hora' => 'datetime:H:i', // solo hora, sin fecha
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function profesional()
    {
        return $this->belongsTo(User::class, 'profesional_id');
    }

<<<<<<< HEAD
    public function pago()
{
    return $this->belongsTo(Pago::class);
}

=======
    public static function boot()
    {
        parent::boot();

        static::creating(function ($turno) {
            if (!$turno->estado) {
                $turno->estado = 'pendiente'; // Estado predeterminado
            }
        });
    }
>>>>>>> 542f9e335a0bea28e7175c9225760181c5fa472e
}
