<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /** Niveles de usuario */
    const LEVEL_ADMIN = 'ADMIN';
    const LEVEL_USER = 'USER';

    /** Estatus de usuario */
    const STATUS_ACTIVE = 'Activo';
    const STATUS_INACTIVE = 'Inactivo';
    const STATUS_DELETED = 'Eliminado';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'level','password','status','top_sale'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Retorna todos los tickets registrado por este usuario.
     * Dicho usuario representa una taquilla
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets() {
        return $this->hasMany('App\Ticket', 'ticket_id');
    }
}
