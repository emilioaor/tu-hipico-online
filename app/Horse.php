<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Horse extends Model
{
    /** Estatus de los caballos */
    const STATUS_ACTIVE = 'Activo';
    const STATUS_RETIRED = 'Retirado';
    const STATUS_DELETED = 'Eliminado';

    protected $table = 'horses';

    protected $fillable = [
        'public_id', 'name','status'
    ];

    /**
     * Retorna las carreras en la que se ha registrado
     * el caballo instanciado
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function runs() {
        return $this->belongsToMany('App\Run', 'run_horse')->withPivot([
            'horse_id','run_id','status','isGain','static_table'
        ]);
    }

    /**
     * Retorna todos los detalles de ticket al que se ha jugado
     * este caballo
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ticketDetails()
    {
        return $this->hasMany('App\TicketDetail', 'horse_id');
    }
}
