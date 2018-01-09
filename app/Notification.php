<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'content',
    ];

    /**
     * Retorna un array con el contenido de todas las notificaciones
     *
     * @return array
     */
    public static function getContentArray()
    {
        $notifications = self::all();
        $response = [];

        foreach ($notifications as $notification) {
            $response[] = $notification->content;
        }

        return $response;
    }
}
