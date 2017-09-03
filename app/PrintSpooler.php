<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrintSpooler extends Model
{
    const STATUS_PENDING = 'Pendiente';
    const STATUS_COMPLETE = 'Completo';

    protected $table = 'print_spooler';

    protected $fillable = [
        'ticket_id','status'
    ];

    public function __construct(array $attributes = []) {
        parent::__construct();
        $this->status = self::STATUS_PENDING;
    }

    /**
     * Retorna el ticket en cola
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket() {
        return $this->belongsTo('App\Ticket', 'ticket_id');
    }
}
