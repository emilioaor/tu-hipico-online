<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Ticket extends Model
{
    /** Estatus de tickets */
    const STATUS_ACTIVE = 'Activo';
    const STATUS_NULL = 'Anulado';
    const STATUS_PAY = 'Pagado';

    const GAIN_PRICE = 500;

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

    /**
     * Retorna el caballo ganador del ticket
     *
     * @return Model|null|static
     */
    public function getHorseGain() {
        $horseGain = TicketDetail::select('horses.id')
            ->join('tickets', 'ticket_id', '=', 'tickets.id')
            ->join('runs', 'tickets.run_id','=', 'run_id')
            ->join('run_horse', 'runs.id', '=', 'run_horse.run_id')
            ->join('horses', 'horses.id', '=', 'run_horse.horse_id')
            ->where('runs.id', $this->run_id)
            ->where('tickets.id', $this->id)
            ->where('run_horse.isGain', true)
            ->first()
        ;

        return $horseGain;
    }

    /**
     * Verifica si un ticket es ganador
     *
     * @return bool
     */
    public function isGain() {
        $horseGain = $this->getHorseGain();

        if (! $horseGain) {
            return false;
        }

        foreach ($this->ticketDetails as $detail) {
            if ($detail->horse_id === $horseGain->id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Indica el monto a pagar al ganador del ticket
     *
     * @return int
     */
    public function payAmount() {
        $horseGain = $this->getHorseGain();

        if (! $horseGain) {
            return 0;
        }

        foreach ($this->ticketDetails as $detail) {
            if ($detail->horse_id == $horseGain->id) {
                $gainAmount = $detail->gain_amount;
            }
        }

        if (! isset($gainAmount)) {
            return 0;
        }

        $amount = ($this->run->dividend + $this->run->bonus) * ($gainAmount / 2500);

        return $amount;
    }
}
