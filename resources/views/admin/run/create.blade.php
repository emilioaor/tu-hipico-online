@extends('layout.base')

@section('header-title', 'Registrar carrera')

@section('header-subtitle', '')

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>  <a href="{{ route('admin.index') }}">Administrador</a> /
            <i class="fa fa-road"></i>  <a href="{{ route('runs.index') }}">Carreras</a> /
            <i class="fa fa-road"></i>  Registrar carrera
        </li>
    </ol>
@endsection

@section('content')

    <form action="{{ route('runs.store') }}" method="post">

        {{ csrf_field() }}

        <div class="row">

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="hippodrome_id">Hipódromo</label>
                    <select name="hippodrome_id" id="hippodrome_id" class="form-control" required>
                        @foreach($hippodromes as $hippodrome)
                            <option value="{{ $hippodrome->id }}">{{ $hippodrome->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="public_id">ID publico</label>
                    <input type="text" class="form-control" id="public_id" name="public_id" maxlength="20" placeholder="ID publico" required>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="date">Fecha de la carrera</label>
                    <input type="date"  class="form-control" id="date" name="date" value="" min="{{ date_format(new \DateTime('now'), 'Y-m-d')  }}" placeholder="Fecha de la carrera" required>
                </div>
            </div>

        </div>

        <div class="form-group">
            <hr>
            <button class="btn btn-success btn-lg"><i class="fa fa-fw fa-save"></i> Registrar</button>
        </div>

    </form>

@endsection