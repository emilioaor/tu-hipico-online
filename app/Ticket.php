<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Ticket extends Model
{
    /** Estatus de tickets */
    const STATUS_ACTIVE = 'Activo';
    const STATUS_NULL = 'Anulado';

    protected $table = 'tickets';

    protected $fillable = [
        'public_id', 'status', 'user_id', 'run_id', 'note'
    ];

    public function __construct() {
        parent::__construct();
        $this->user_id = Auth::check() ? Auth::user()->id : 0;
        $this->status = self::STATUS_ACTIVE;
    }

    /**
     * Retorna el detalle de este ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ticketDetails() {
        return $this->hasMany('App\TicketDetail', 'ticket_id');
    }

    /**
     * Retorna el usuario que registro el ticket. Dicho usuario
     * representa una taquilla.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Retorna la carrera para la que se aposto este ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function run() {
        return $this->belongsTo('App\Run', 'run_id');
    }

    /**
     * Retorna todos los elementos en cola de impresion para
     * este ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function printSpooler() {
        return $this->hasMany('App\PrintSpooler', 'ticket_id');
    }

    /**
     * Calcula el total invertido en el ticket
     *
     * @return int
     */
    public function totalAmount() {
        $total = 0;

        foreach ($this->ticketDetails as $detail) {
            $total += ($detail->horse->runs()->find($this->id)->pivot->static_table * $detail->tables);
            $total += $detail->gain_amount;
        }

        return $total;
    }

    /**
     * Calcula el total invertido en el ticket y
     * que estatus este activo
     *
     * @return int
     */
    public function totalActiveAmount() {
        $total = 0;

        foreach ($this->ticketDetails as $detail) {
            if ($detail->status === TicketDetail::STATUS_ACTIVE) {
                $total += ($detail->horse->runs()->find($this->run->id)->pivot->static_table * $detail->tables);
                $total += $detail->gain_amount;
            }
        }

        return $total;
    }

    /**
     * Calcula el total por tablas
     *
     * @return int
     */
    public function totalForTables() {
        $total = 0;

        foreach ($this->ticketDetails as $detail) {
            if ($detail->status === TicketDetail::STATUS_ACTIVE) {
                $total += ($detail->horse->runs()->find($this->run->id)->pivot->static_table * $detail->tables);
            }
        }

        return $total;
    }

    /**
     * Calcula el total por ganador
     *
     * @return int
     */
    public function totalForGains() {
        $total = 0;

        foreach ($this->ticketDetails as $detail) {
            if ($detail->status === TicketDetail::STATUS_ACTIVE) {
                $total += $detail->gain_amount;
            }
        }

        return $total;
    }


    /**
     * Indica si el ticket tiene detalles activos
     *
     * @return bool
     */
    public function haveDetailActive() {

        foreach ($this->ticketDetails as $detail) {

            if ($detail->status === TicketDetail::STATUS_ACTIVE) {
                return true;
            }
        }

        return false;
    }


    /**
     * Verifica si este ticket esta en cola de impresion
     *
     * @return bool
     */
    public function isPrintSpooler() {

        foreach ($this->printSpooler as $printSpooler) {
            if ($printSpooler->status === PrintSpooler::STATUS_PENDING) {
                return true;
            }
        }

        return false;
    }
}
