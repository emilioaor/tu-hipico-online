@extends('layout.base')

@section('header-title', 'Ingreso de usuario')

@section('header-subtitle', '')

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>  <a href="{{ route('index.login') }}">Ingreso</a>
        </li>
    </ol>
@endsection

@section('content')

    <div class="row">

        <div class="col-sm-6 col-sm-offset-3">
            <form action="{{ route('index.auth') }}" method="post">

                {{ csrf_field() }}

                <div class="form-group">
                    <label for="username">Usuario</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Usuario" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" required>
                </div>

                <div class="form-group text-center">
                    <button class="btn btn-primary">Ingresar</button>
                </div>

            </form>
        </div>

    </div>

@endsection