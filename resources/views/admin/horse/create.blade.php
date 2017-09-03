@extends('layout.base')

@section('header-title', 'Registrar caballo')

@section('header-subtitle', '')

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>  <a href="{{ route('admin.index') }}">Administrador</a> /
            <i class="glyphicon glyphicon-knight"></i>  <a href="{{ route('horses.index') }}">Caballos</a> /
            <i class="glyphicon glyphicon-knight"></i>  Registrar caballo
        </li>
    </ol>
@endsection

@section('content')

    <form action="{{ route('horses.store') }}" method="post">

        {{ csrf_field() }}

        <div class="row">

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="name">Nombre del caballo</label>
                    <input type="text"  class="form-control" id="name" name="name" value="" placeholder="Nombre del caballo" maxlength="50" required>
                </div>
            </div>

        </div>

        <div class="form-group">
            <hr>
            <button class="btn btn-success btn-lg"><i class="fa fa-fw fa-save"></i> Registrar</button>
        </div>

    </form>

@endsection