@extends('layout.base')

@section('header-title', 'Editar carrera')

@section('header-subtitle', $run->public_id)

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>  <a href="{{ route('admin.index') }}">Administrador</a> /
            <i class="fa fa-road"></i>  <a href="{{ route('runs.index') }}">Carreras</a> /
            <i class="fa fa-road"></i>  {{ $run->public_id }}
        </li>
    </ol>
@endsection

@section('content')

    <form action="{{ route('runs.update', ['run' => $run->id]) }}" method="post">

        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <div class="row">

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="hippodrome_id">Hipódromo</label>
                    <select name="hippodrome_id" id="hippodrome_id" class="form-control" required>
                        @foreach($hippodromes as $hippodrome)
                            <option value="{{ $hippodrome->id }}" {{ $run->hippodrome_id === $hippodrome->id ? 'selected' : '' }}>{{ $hippodrome->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="public_id">ID publico</label>
                    <input type="text"  class="form-control" id="public_id" value="{{ $run->public_id }}" placeholder="ID publico" disabled>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="date">Fecha de la carrera</label>
                    <input type="date"  class="form-control" id="date" name="date" value="{{ date_format($run->dateInDateTime(), 'Y-m-d') }}" placeholder="Fecha de la carrera" min="{{ date_format(new \DateTime('now'), 'Y-m-d')  }}" required>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="status">Estatus</label>
                    <input type="text"  class="form-control" id="status" value="{{ $run->status }}" placeholder="Estatus" disabled>
                </div>
            </div>

        </div>

        <div class="form-group">
            <table class="table table-responsive">
                <thead>
                    <th width="60%">Caballos registrados</th>
                    <th width="20%">Precio de tabla fija</th>
                    <th width="5%"></th>
                </thead>

                <tbody id="spaceHorses">
                    @foreach($run->orderedHorses() as $horse)
                        <tr id="row{{ $horse->id }}">
                            <td>
                                {{ $horse->public_id . ' - ' . $horse->name }}
                                <input type="hidden" name="horses[]" value="{{ $horse->id }}">
                            </td>
                            <td>
                                <input type="number" class="form-control" value="{{ $horse->pivot->static_table }}" name="staticTable[{{ $horse->id }}]" min="0" placeholder="Precio de tabla fija" required>
                            </td>
                            <td>
                                <button type="button" onclick="removeHorse({{ $horse->id }});" class="btn btn-danger"><i class="fa fa-fw fa-remove"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2">
                            <select id="newHorse" class="form-control">
                                <option value="0">- Seleccione un caballo -</option>
                                @foreach($horses as $horse)
                                    <option data-label="{{ $horse->public_id . ' - ' . $horse->name }}"
                                            id="option{{ $horse->id }}"
                                            value="{{ $horse->id }}"
                                            style="{{ in_array($horse->id, $selectedHorses) ? 'display:none' : '' }}">

                                        {{ $horse->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>

                        <td>
                            <button type="button" onclick="addHorse();" class="btn btn-success"><i class="fa fa-fw fa-plus"></i></button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="form-group">
            <hr>
            <button class="btn btn-success btn-lg"><i class="fa fa-fw fa-save"></i> Actualizar</button>
            <a href="{{ route('runs.show', ['run' => $run->id]) }}" class="btn btn-primary btn-lg"><i class="fa fa-fw fa-eye"></i> Vista en vivo</a>
        </div>

    </form>
@endsection

@section('js')
    <script src="{{ asset('js/runController.js') }}"></script>
@endsection