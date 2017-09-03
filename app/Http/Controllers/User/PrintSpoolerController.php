<?php

namespace App\Http\Controllers\User;

use App\PrintSpooler;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class PrintSpoolerController extends Controller
{

    /**
     * Carga vista de cola de impresion
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {

        if (Auth::user()->level === User::LEVEL_ADMIN) {
            //  Cargar la cola de impresion de todas las taquillas
            $printSpooler = PrintSpooler::select('print_spooler.*')
                ->orderBy('print_spooler.id', 'DESC')
                ->join('tickets', 'ticket_id', 'tickets.id')
                ->join('users', 'user_id', 'users.id')
                ->paginate(20)
            ;

        } elseif (Auth::user()->level === User::LEVEL_USER) {
            //  Carga solo la cola de impresion de esta taquilla
            $printSpooler = PrintSpooler::select('print_spooler.*')
                ->orderBy('print_spooler.id', 'DESC')
                ->join('tickets', 'ticket_id', 'tickets.id')
                ->join('users', 'user_id', 'users.id')
                ->where('user_id', Auth::user()->id)
                ->paginate(20)
            ;
        }

        return view('user.printSpooler.index')->with(['printSpooler' => $printSpooler]);
    }
}
