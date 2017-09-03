<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Crea un mensaje de alerta en la session
     *
     * @param $message
     * @param string $type
     */
    protected function sessionMessage($message, $type = 'alert-success') {
        Session::flash('alert-message', $message);
        Session::flash('alert-type', $type);
    }

    /**
     * Hace una busqueda con comodines en base de datos
     *
     * @param $search
     * @param $class
     * @param array $fields
     * @param int $paginate
     * @return mixed
     */
    protected function search($search, $class, array $fields, $paginate = 0) {

        $querySearch = '%' . str_replace(' ', '%', $search) . '%';

        $query = $class::select('*');

        foreach ($fields as $i => $field) {

            if ($i === 0) {
                $query->where($field, 'like', $querySearch);
            } else {
                $query->orWhere($field, 'like', $querySearch);
            }
        }

        if ($paginate) {
            return $query->paginate($paginate);
        }

        return $query->get();
    }
}
