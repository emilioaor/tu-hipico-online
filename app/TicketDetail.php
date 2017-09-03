<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketDetail extends Model
{
    /** Estatus de detalle de ticket */
    const STATUS_ACTIVE = 'Activo';
    const STATUS_NULL = 'Anulado';

    protected $table = 'ticket_details';

    protected $fillable = [
        'ticket_id', 'horse_id', 'tables', 'status', 'gain_amount'
    ];


    public function __construct($ticketId = null, $horseId = null) {
        parent::__construct();
        $this->status = self::STATUS_ACTIVE;
        $this->ticket_id = $ticketId;
        $this->horse_id = $horseId;
    }

    /**
     * Retorna el ticket al que pertenece este detalle
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket() {
        return $this->belongsTo('App\Ticket', 'ticket_id');
    }

    /**
     * Obtiene el caballo asociado a este detalle
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function horse() {
        return $this->belongsTo('App\Horse', 'horse_id');
    }

}
