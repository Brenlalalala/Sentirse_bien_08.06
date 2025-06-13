<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'servicio_id', 'fecha', 'hora', 'estado'];

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

    public function pago()
{
    return $this->belongsTo(Pago::class);
}

}
