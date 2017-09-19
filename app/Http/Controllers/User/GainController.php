<?php

namespace App\Http\Controllers\User;

use App\Horse;
use App\PrintSpooler;
use App\Run;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Ticket;
use App\TicketDetail;
use DB;
use Auth;
use PDF;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Controlador para el control de ganadores
 *
 * @author Emilio Ochoa <emilioaor@gmail.com>
 * @package App\Http\Controllers
 */
class GainController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! empty($request->search)) {
            $tickets = $this->search($request->search, Ticket::class, [
                'public_id',
            ], 20);

            return view('user.gain.index')->with('tickets', $tickets);
        }

        $tickets = Ticket::orderBy('created_at', 'DESC')->paginate(20);

        return view('user.gain.index')->with('tickets', $tickets);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $activeRun = Run::where('status', Run::STATUS_OPEN)->first();

        if (! $activeRun) {
            $this->sessionMessage('Disculpe, no hay una carrera activa', 'alert-danger');

            return redirect()->route('gains.index');
        }

        $retiredHorses = $this->getRetiredHorses($activeRun->id);

        return view('user.gain.create')->with([
            'activeRun' => $activeRun,
            'retiredHorses' => $retiredHorses
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

            $run = Run::find($request->run_id);

            if (! $this->canCreateTicket($request, $run) ) {
                DB::rollback();
                return redirect()->route('gains.create');
            }

            $count = 1 + Ticket::all()->count();

            $ticket = new Ticket();

            $ticket->public_id = 'TICK-00000000' . $count;
            $ticket->run_id = $request->run_id;
            $ticket->save();

            foreach ($run->horses as $horse) {

                if (! empty($request->table[$horse->id]) || ! empty($request->gain[$horse->id])) {

                    $ticketDetail = new TicketDetail($ticket->id, $horse->id);
                    $ticketDetail->tables = ! empty($request->table[$horse->id]) ? $request->table[$horse->id] : 0;
                    $ticketDetail->gain_amount = ! empty($request->gain[$horse->id]) ? $request->gain[$horse->id] : 0;
                    $ticketDetail->save();
                }
            }

            $printSpooler = new PrintSpooler();
            $printSpooler->ticket_id = $ticket->id;
            $printSpooler->save();

        DB::commit();

        $this->sessionMessage('Ticket registrado');

        return redirect()->route('gains.create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);

        return view('user.gain.show')->with('ticket', $ticket);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        DB::beginTransaction();

            $ticket = Ticket::findOrFail($id);

            $ticket->status = Ticket::STATUS_NULL;
            $ticket->note = $request->note;
            $ticket->save();

            //  Anular todos los detalles de este ticket
            TicketDetail::where('ticket_id', $id)
                ->update(['status' => TicketDetail::STATUS_NULL])
            ;

        DB::commit();

        $this->sessionMessage('Ticket anulado');

        return redirect()->route('gains.show', ['gain' => $id]);
    }

    /**
     * Obtienes los ID de los caballos retirados
     * de una carrera
     *
     * @param $runId
     * @return array
     */
    private function getRetiredHorses($runId) {

        $retiredHorses = [];

        $horses = DB::table('run_horse')
            ->select('horse_id')
            ->where('run_id', $runId)
            ->where('status', Horse::STATUS_RETIRED)
            ->get()
        ;

        foreach ($horses as $horse) {
            $retiredHorses[] = $horse->horse_id;
        }

        return $retiredHorses;
    }

    /**
     * Verifica si se puede registrar el ticket
     *
     * @param Request $request
     * @param Run $run
     * @return bool
     */
    private function canCreateTicket(Request $request, Run $run) {

        if ($run->status === Run::STATUS_CLOSE) {
            $this->sessionMessage('Disculpe la carrera ya cerro', 'alert-danger');
            return false;
        }

        $haveGame = false;

        foreach ($run->horses as $horse) {

            /* -------------------------------------------
             *   Verifico que exista una apuesta por tabla
             *   o ganador por al menos un caballo
             * ------------------------------------------- */

            if ( ! empty($request->table[ $horse->id ]) || ! empty($request->gain[ $horse->id ]) ) {
                $haveGame = true;
                break;
            }
        }

        if (! $haveGame) {
            $this->sessionMessage('Debe apostar por al menos un caballo', 'alert-danger');
            return false;
        }

        $retiredHorses = $this->getRetiredHorses($run->id);

        foreach ($run->horses as $horse) {

            /*  --------------------------------------------------
             *  Verifico que no existan apuestas por caballos retirados
             * y en caso de que existan reverso el ticket
             *  --------------------------------------------------- */

            if (in_array($horse->id, $retiredHorses) && (! empty($request->table[ $horse->id ]) || ! empty($request->gain[ $horse->id ])) ) {
                $this->sessionMessage('No puede apostar por un caballo retirado de la carrera', 'alert-danger');
                return false;
            }
        }

        return true;
    }

    /**
     * Genera una vista en PDF para el ticket
     *
     * @param $ticketId
     * @return $this
     */
    public function generateTicket($ticketId) {

        $ticket = Ticket::find($ticketId);

        if (! $ticket) {
            $this->sessionMessage('Ticket no encontrado', 'alert-danger');
            return redirect()->route('gains.index');
        }

        $pdf = PDF::loadView('pdf.ticket', ['ticket' => $ticket])->setPaper('a7');

        return $pdf->stream();
    }

    /**
     * Retorna un json con el valor en tablas para los caballos
     * registrados a la carrera activa
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function valueInTablesRest(Request $request) {

        if ($request->isXmlHttpRequest()) {

            $run = Run::where('status', Run::STATUS_OPEN)->firstOrFail();
            $data = [];

            foreach ($run->horses as $horse) {
                $data[] = [
                    'horseId' => $horse->id,
                    'static_table' => (int) $horse->pivot->static_table,
                ];
            }

            return new JsonResponse(['data' => $data]);
        }

        return new Response('Not found', 404);
    }

    /**
     * Descargar ticket PDF
     *
     * @param $ticketId
     * @return mixed
     */
    public function downloadTicket($ticketId) {

        $ticket = Ticket::findOrFail($ticketId);

        $pdf = PDF::loadView('pdf.ticket', ['ticket' => $ticket])->setPaper('a7');

        return $pdf->download($ticket->public_id . '.pdf');
    }

    /**
     * Agrega un ticket a la cola de impresion
     *
     * @param $ticketId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function printTicket($ticketId) {

        $printSpooler = new PrintSpooler();
        $printSpooler->ticket_id = $ticketId;
        $printSpooler->save();

        $this->sessionMessage('Ticket agregado a la cola de impresión');

        return redirect()->route('gains.show', ['ticket' => $ticketId]);
    }

    /**
     * Marca un ticket como pagado
     *
     * @param $ticketId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function payTicket($ticketId) {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->status = Ticket::STATUS_PAY;
        $ticket->save();

        $this->sessionMessage('Ticket pagado');

        return redirect()->route('gains.show', ['gain' => $ticketId]);
    }
}
