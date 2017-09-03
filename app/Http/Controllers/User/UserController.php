<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Run;

/**
 * Controlador para usuarios autenticados
 *
 * @author Emilio Ochoa <emilioaor@gmail.com>
 * @package App\Http\Controllers\User
 */
class UserController extends Controller
{

    public function index() {
        $dateStart = new \DateTime('now');
        $dateEnd = new \DateTime('now');

        $dateStart->setTime(0, 0, 0);
        $dateEnd->setTime(23, 59, 59);

        $runs = Run::where('date', '>=', $dateStart)->where('date', '<=', $dateEnd)->get();
        $activeRun = Run::where('status', Run::STATUS_OPEN)->get();

        $activeRun = count($activeRun) ? $activeRun[0] : null;

        return view('user.index')->with(['runs' => $runs, 'activeRun' => $activeRun]);
    }
}
