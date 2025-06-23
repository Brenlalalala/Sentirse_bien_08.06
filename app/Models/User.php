<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use HasFactory, Notifiable;
    
    protected $guard_name = 'web';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',  // roles
        'es_admin', // si usás este booleano también
        'celular',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'es_admin' => 'boolean',
    ];

    // Métodos para chequear rol
    public function isAdmin()
    {
        return $this->es_admin == true || $this->role === 'admin';
    }

   public function isCliente()
{
    return $this->hasRole('cliente');
}

    public function isProfesional()
    {
        //return $this->role === 'profesional';
        return $this->hasRole('profesional');
    }

    public function turnos() {
    return $this->hasMany(Turno::class);
}

public function pagos()
{
    return $this->hasMany(Pago::class);
}

//para poder ver que scios. tiene un profesional
public function servicios()
{
    return $this->belongsToMany(Servicio::class, 'profesional_servicio', 'user_id', 'servicio_id');
}

///horarios disponibles de los profesionales
public function horarios()
{
    return $this->hasMany(HorarioProfesional::class);
}


}


