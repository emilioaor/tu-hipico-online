<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Run;

/**
 * Controlador para el manejo de rutas de la
 * raiz de admin
 *
 * @author Emilio Ochoa <emilioaor@gmail.com>
 * @package App\Http\Controllers\Admin
 */
class AdminController extends Controller
{

    /**
     * Carga vista principal del administrador
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {

        $dateStart = new \DateTime('now');
        $dateEnd = new \DateTime('now');

        $dateStart->setTime(0, 0, 0);
        $dateEnd->setTime(23, 59, 59);

        $runs = Run::where('date', '>=', $dateStart)->where('date', '<=', $dateEnd)->get();

        return view('admin.index')->with(['runs' => $runs]);
    }
}
