@extends('layout.base')

@section('header-title', 'Registrar hipódromo')

@section('header-subtitle', '')

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>  <a href="{{ route('admin.index') }}">Administrador</a> /
            <i class="glyphicon glyphicon-tower"></i>  <a href="{{ route('hippodromes.index') }}">Hipódromos</a> /
            <i class="glyphicon glyphicon-tower"></i>  Registrar hipódromo
        </li>
    </ol>
@endsection

@section('content')

    <form action="{{ route('hippodromes.store') }}" method="post">

        {{ csrf_field() }}

        <div class="row">

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="name">Nombre del hipódromo</label>
                    <input type="text"  class="form-control" id="name" name="name" value="" max="40" placeholder="Nombre del hipódromo" required>
                </div>
            </div>

        </div>

        <div class="form-group">
            <hr>
            <button class="btn btn-success btn-lg"><i class="fa fa-fw fa-save"></i> Registrar</button>
        </div>

    </form>

@endsection