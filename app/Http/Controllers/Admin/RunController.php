<?php

namespace App\Http\Controllers\Admin;

use App\Ticket;
use App\TicketDetail;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Run;
use App\Horse;
use App\Http\Requests\Admin\Run\CreateRunRequest;
use App\Http\Requests\Admin\Run\UpdateRunRequest;
use DB;
use Symfony\Component\HttpFoundation\Response;
use App\Hippodrome;
use Illuminate\Database\Eloquent\Model;

/**
 * Controlador para mantenimiento de carreras
 *
 * @author Emilio Ochoa <emilioaor@gmail.com>
 * @package App\Http\Controllers\Admin
 */
class RunController extends Controller
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
            $runs = $this->search($request->search, Run::class, [
                'public_id',
            ], 20);

            return view('admin.run.index')->with('runs', $runs);
        }

        $runs = Run::orderBy('date', 'DESC')->orderBy('id', 'DESC')->paginate(20);

        return view('admin.run.index')->with('runs', $runs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hippodromes = Hippodrome::where('status', Hippodrome::STATUS_ACTIVE)->get();

        return view('admin.run.create')->with(['hippodromes' => $hippodromes]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateRunRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRunRequest $request)
    {
        $run = new Run($request->all());
        $run->save();

        $this->sessionMessage('Carrera registrada');

        return redirect()->route('runs.edit', ['run' => $run->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $run = Run::find($id);

        return view('admin.run.show')->with(['run' => $run]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $run = Run::findOrFail($id);

        if ($run->status !== Run::STATUS_PENDING) {
            $this->sessionMessage('Solo se pueden editar carreras con estatus "Pendiente"', 'alert-danger');
            return redirect()->route('runs.index');
        }

        $horses = Horse::where('status', Horse::STATUS_ACTIVE)->orderBy('name')->get();
        $hippodromes = Hippodrome::where('status', Hippodrome::STATUS_ACTIVE)->get();

        $selectedHorses = [];

        foreach ($run->horses as $horse) {
            $selectedHorses[] = $horse->id;
        }

        return view('admin.run.edit')->with([
            'run' => $run,
            'horses' => $horses,
            'selectedHorses' => $selectedHorses,
            'hippodromes' => $hippodromes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRunRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRunRequest $request, $id)
    {
        DB::beginTransaction();

            $run = Run::findOrFail($id);

            $run->date = $request->date;
            $run->hippodrome_id = $request->hippodrome_id;
            $run->save();

            //  Elimina todos los caballos asociados a la carrera
            DB::table('run_horse')->where('run_id', $id)->delete();

            //  Agrega los caballos seleccionados a la carrera
            $order = 1;
            foreach ($request->horses as $horseId) {

                $horse = Horse::findOrFail($horseId);

                $staticTable = ! empty($request->staticTable[$horseId]) ? $request->staticTable[$horseId] : 0;

                $run->horses()->attach($horse, [
                    'static_table' => $staticTable,
                    'order' => $order,
                ]);

                $order++;
            }

        DB::commit();

        $this->sessionMessage('Carrera actualizada');

        return redirect()->route('runs.edit', ['run' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    /**
     * Cambiar el estatus de una carrera
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeStatus($id) {

        $run = Run::find($id);

        if ($run->status === Run::STATUS_PENDING) {

            if (count($run->horses) < 2) {
                $this->sessionMessage('Debe registrar al menos 2 caballos a la carrera', 'alert-danger');
                return redirect()->route('runs.show', ['run' => $run->id]);
            }

            $activeRuns = Run::where('status', Run::STATUS_OPEN)->get();

            if (count($activeRuns)) {
                $this->sessionMessage('Solo puede tener una carrera abierta a la vez', 'alert-danger');
                return redirect()->route('runs.show', ['run' => $run->id]);
            }

            $this->sessionMessage('Carrera abierta');
            $run->status = Run::STATUS_OPEN;

        } elseif ($run->status === Run::STATUS_OPEN) {
            $this->sessionMessage('Carrera cerrada');
            $run->status = Run::STATUS_CLOSE;
        }

        $run->save();

        return redirect()->route('runs.show', ['run' => $run->id]);
    }

    /**
     * Retiro de caballo a ultima hora
     *
     * @param $runId
     * @param $horseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function retireHorse($runId, $horseId) {

        DB::beginTransaction();

            // Cambia el estatus del caballo para esta carrera
            DB::table('run_horse')
                ->where('run_id', $runId)
                ->where('horse_id', $horseId)
                ->update(['status' => Horse::STATUS_RETIRED])
            ;

            // Anular los detalles de tickets asociados a este caballo y carrera
            with($ticketDetail = new TicketDetail)->timestamps = false;

            $ticketDetail
                ->join('tickets', 'ticket_id', '=', 'tickets.id')
                ->where('horse_id', $horseId)
                ->where('run_id', $runId)
                ->update([
                    'ticket_details.status' => TicketDetail::STATUS_NULL,
                    'ticket_details.updated_at' => Carbon::now(),
                ])
            ;

            /*  -------------------------------------------------------------
             *  Comprobar si alguno de los tickets tiene todos los detalles
             *  anulados. En ese caso se anula el ticket completo
             *  ------------------------------------------------------------- */

            $tickets = Ticket::where('run_id', $runId)->get();

            foreach ($tickets as $ticket) {

                if (! $ticket->haveDetailActive()) {

                    $ticket->status = Ticket::STATUS_NULL;
                    $ticket->note = 'Anulado por retiro de caballo';
                    $ticket->save();
                }
            }

        DB::commit();

        $this->sessionMessage('Caballo retirado');

        return redirect()->route('runs.show', ['run' => $runId]);
    }


    /**
     * Servicio REST para obtener todos los tickets
     * asociados a una carrera
     *
     * @param $runId
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function ticketsForRunRest($runId, Request $request) {

        if ($request->isXmlHttpRequest()) {

            $run = Run::find($runId);
            $data = [];

            foreach ($run->tickets as $ticket) {

                $data[] = [
                    'public_id' => $ticket->public_id,
                    'user' => $ticket->user->name,
                    'amount' => $ticket->totalActiveAmount()
                ];
            }

            return new JsonResponse(['data' => $data]);
        }

        return new Response('Not found', 404);
    }

    /**
     * Establece el ganador de la carrera
     *
     * @param $runId
     * @param $horseId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setGained($runId, $horseId) {

        $run = Run::findOrFail($runId);

        if ($run->status !== Run::STATUS_CLOSE) {
            $this->sessionMessage('Solo puede marcar un ganador una vez terminada la carrera', 'alert-danger');

            return redirect()->route('runs.show', ['run' => $runId]);
        }

        $runHorse = DB::table('run_horse')->where('run_id', $runId)->where('horse_id', $horseId)->first();

        if ($runHorse->status !== Horse::STATUS_ACTIVE) {
            $this->sessionMessage('Este caballo no esta activo en la carrera', 'alert-danger');

            return redirect()->route('runs.show', ['run' => $runId]);
        }

        DB::beginTransaction();

            //  Marco el resto de los caballos como perdedores
            DB::table('run_horse')
                ->where('run_id', $runId)
                ->update(['isGain' => false])
            ;

            //  Marco ganador al caballo seleccionado
            DB::table('run_horse')
                ->where('run_id', $runId)
                ->where('horse_id', $horseId)
                ->update(['isGain' => true])
            ;

        DB::commit();

        $this->sessionMessage('Caballo marcado como ganador');

        return redirect()->route('runs.show', ['run' => $runId]);
    }

    /**
     * Actualiza el valor de las tablas para una carrera
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTable(Request $request, $id) {

        $run = Run::findOrFail($id);

        DB::beginTransaction();

            foreach ($run->horses as $horse) {

                if ( isset( $request->table[ $horse->id ] ) ) {
                    $horse->pivot->static_table = $request->table[ $horse->id ];
                    $horse->pivot->save();
                }
            }

            $this->sessionMessage('Tablas actualizadas');

        DB::commit();

        return redirect()->route('runs.show', ['run' => $id]);
    }

    /**
     * Actualiza el dividendo para una carrera
     *
     * @param $runId
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateDividend($runId, Request $request) {

        $run = Run::findOrFail($runId);

        $run->dividend = $request->dividend;
        $run->bonus = $request->bonus;
        $run->save();

        $this->sessionMessage('Valores actualizado');

        return redirect()->route('runs.show', ['run' => $runId]);
    }

}
