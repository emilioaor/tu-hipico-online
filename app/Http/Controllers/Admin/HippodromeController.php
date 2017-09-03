<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Hippodrome;
use App\Http\Requests\Admin\Hippodrome\CreateHippodromeRequest;

/**
 * Maneja todas las rutas relacionadas con
 * hipodromos
 *
 * @author Emilio Ochoa <emilioaor@gmail.com>
 * @package App\Http\Controllers\Admin
 */
class HippodromeController extends Controller
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
            $hippodromes = $this->search($request->search, Hippodrome::class, [
                'public_id',
                'name'
            ], 20);

            return view('admin.hippodrome.index')->with('hippodromes', $hippodromes);
        }

        $hippodromes = Hippodrome::where('status', Hippodrome::STATUS_ACTIVE)
            ->orderBy('id', 'DESC')
            ->paginate(20)
        ;

        return view('admin.hippodrome.index')->with(['hippodromes' => $hippodromes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.hippodrome.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateHippodromeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateHippodromeRequest $request)
    {
        $hippodrome = new Hippodrome($request->all());

        $count = 1 + Hippodrome::all()->count();

        $hippodrome->public_id = 'HIP-000' . $count;
        $hippodrome->save();

        $this->sessionMessage('Hipódromo regisrado');

        return redirect()->route('hippodromes.edit', ['hippodrome' => $hippodrome->id]);
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
        $hippodrome = Hippodrome::findOrFail($id);

        return view('admin.hippodrome.edit')->with(['hippodrome' => $hippodrome]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CreateHippodromeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateHippodromeRequest $request, $id)
    {
        $hippodrome = Hippodrome::findOrFail($id);

        $hippodrome->update($request->all());

        $this->sessionMessage('Hipódromo actualizado');

        return redirect()->route('hippodromes.edit', ['hippodrome' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $hippodrome = Hippodrome::findOrFail($id);

        $hippodrome->status = Hippodrome::STATUS_DELETED;
        $hippodrome->save();

        $this->sessionMessage('Hipódromo eliminado');

        return redirect()->route('hippodromes.index');
    }
}
