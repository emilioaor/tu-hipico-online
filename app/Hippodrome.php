<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Hippodrome extends Model
{
    /** Estatus de hiprodromo */
    const STATUS_ACTIVE = 'Activo';
    const STATUS_DELETED = 'Eliminado';

    protected $table = 'hippodromes';

    protected $fillable = [
        'public_id', 'name', 'status'
    ];

    /**
     * Retorna todas las carreras asociadas a
     * este hipodromo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function runs() {
        return $this->hasMany('App\Run', 'hippodrome_id');
    }
}
