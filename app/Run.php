<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Run extends Model
{
    /** Estatus de las carreras */
    const STATUS_PENDING = 'Pendiente';
    const STATUS_OPEN = 'Abierta';
    const STATUS_CLOSE = 'Cerrada';

    protected $table = 'runs';

    protected $fillable = [
        'public_id', 'date', 'status', 'hippodrome_id', 'dividend', 'bonus'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->status = self::STATUS_PENDING;
    }

    /**
     * Retorna todos los caballos registrados en la carrera
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function horses()
    {
        return $this->belongsToMany('App\Horse', 'run_horse')->withPivot([
            'run_id','horse_id','status','isGain','static_table','order',
        ]);
    }


    /**
     * Retorna un objeto DateTime con la fecha
     * registrada
     *
     * @return \DateTime
     */
    public function dateInDateTime()
    {
        $dateTime = new \DateTime($this->date);

        return $dateTime;
    }

    /**
     * Retorna todos los tickes que se apostaron en esta carrera
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany('App\Ticket', 'run_id');
    }


    /**
     * Retorna la suma de los montos de todos los
     * tickets asociados a esta carrera
     *
     * @return int
     */
    public function totalTicketsAmount()
    {
        $total = 0;

        foreach ($this->tickets as $ticket) {
            $total += $ticket->totalActiveAmount();
        }

        return $total;
    }


    /**
     * Retorna el hippodrome al cual pertenece esta carrera
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hippodrome() {
        return $this->belongsTo('App\Hippodrome', 'hippodrome_id');
    }


    /**
     * Retorna el caballo ganador de la carrera
     *
     * @return null|Horse
     */
    public function horseGained() {

        foreach ($this->horses as $horse) {

            if ($horse->pivot->isGain) {
                return $horse;
            }
        }

        return null;
    }


    /**
     * Verifica si un caballo asociado a esta carrera
     * esta activo para esta carrera
     *
     * @param $horseId
     * @return bool
     */
    public function hasActiveHorse($horseId) {

        foreach ($this->horses as $horse) {

            if ($horse->id === $horseId && $horse->pivot->status === Horse::STATUS_ACTIVE) {
                return true;
            }
        }

        return false;
    }

    /**
     * Retorna un array de ID de caballos asociados
     * a esta carrera
     *
     * @return array
     */
    public function horsesIdArray() {

        $currentHorsesId = [];

        foreach ($this->horses as $horse) {
            $currentHorsesId[] = $horse->id;
        }

        return $currentHorsesId;
    }

    /**
     * Retorna los caballos asociados a la carrera en el mismo
     * orden que se registraron
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function orderedHorses() {
        return $this->horses()->orderBy('run_horse.order')->get();
    }
}
