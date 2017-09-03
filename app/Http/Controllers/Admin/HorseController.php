<?php

namespace App\Http\Controllers\Admin;

use App\Run;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Horse;
use App\Http\Requests\Admin\Horse\CreateHorseRequest;
use DB;


/**
 * Controlador para mantenimiento de caballos
 *
 * @author Emilio Ochoa <emilioaor@gmail.com>
 * @package App\Http\Controllers\Admin
 */
class HorseController extends Controller
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
            $horses = $this->search($request->search, Horse::class, [
                'public_id',
                'status',
                'name',
            ], 20);

            return view('admin.horse.index')->with('horses', $horses);
        }

        $horses = Horse::orderBy('id', 'DESC')->paginate(20);

        return view('admin.horse.index')->with('horses', $horses);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.horse.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateHorseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateHorseRequest $request)
    {
        $uniqueName = Horse::where('name', $request->name)->first();

        if ($uniqueName) {
            $this->sessionMessage('El nombre del caballo ya existe', 'alert-danger');
            return redirect()->route('horses.create');
        }

        $count = 1 + Horse::all()->count();

        $horse = new Horse($request->all());

        $horse->public_id = 'CAB-000' . $count;
        $horse->save();

        $this->sessionMessage('Caballo registrado');

        return redirect()->route('horses.edit', ['horse' => $horse->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $horse = Horse::findOrFail($id);

        return view('admin.horse.edit')->with('horse', $horse);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CreateHorseRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateHorseRequest $request, $id)
    {
        $uniqueName = Horse::where('name', $request->name)->first();

        if ($uniqueName && $uniqueName->id != $id) {
            $this->sessionMessage('El nombre del caballo ya existe', 'alert-danger');
            return redirect()->route('horses.edit', ['horse' => $id]);
        }

        $horse = Horse::findOrFail($id);

        $horse->name = $request->name;
        $horse->save();

        $this->sessionMessage('Caballo actualizado');

        return redirect()->route('horses.edit', ['horse' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $horse = Horse::findOrFail($id);

        DB::beginTransaction();

            $horse->status = Horse::STATUS_DELETED;
            $horse->save();

            //  Elimina el caballo de todas las cerreras pendientes
            DB::table('run_horse')
                ->join('runs', 'runs.id', '=', 'run_id')
                ->where('horse_id', $horse->id)
                ->where('runs.status', Run::STATUS_PENDING)
                ->delete()
            ;

            $this->sessionMessage('El caballo fue eliminado de las carreras');

        DB::commit();

        return redirect()->route('horses.edit', ['horse' => $horse->id]);
    }
}
