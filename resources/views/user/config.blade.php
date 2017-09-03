@extends('layout.base')

@section('header-title', 'Configuración')

@section('header-subtitle', Auth::user()->name)

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-gear"></i>  <a href="{{ route('user.index') }}">Configuración</a>
        </li>
    </ol>
@endsection

@section('content')

    <form action="{{ route('user.config.changePassword', ['user' => Auth::user()->id]) }}" method="post">

        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <div class="row">

            <div class="col-sm-3">
                
                <div class="form-group">
                    <label for="old_password">Vieja contraseña</label>
                    <input type="password" class="form-control" name="old_password" id="old_password" maxlength="20" placeholder="Vieja contraseña" required>
                </div>
            </div>

            <div class="col-sm-3">

                <div class="form-group">
                    <label for="new_password1">Nueva contraseña</label>
                    <input type="password" class="form-control" name="new_password1" id="new_password1" maxlength="20" placeholder="Nueva contraseña" required>
                </div>
            </div>

            <div class="col-sm-3">

                <div class="form-group">
                    <label for="new_password2">Repetir nueva contraseña</label>
                    <input type="password" class="form-control" name="new_password2" id="new_password2" maxlength="20" placeholder="Repetir nueva contraseña" required>
                </div>
            </div>
            
        </div>


        <div class="row">
            <div class="col-xs-12">
                <div class="form-group">
                    <button class="btn btn-success btn-lg"><i class="fa fa-fw fa-save"></i> Cambiar contraseña</button>
                </div>
            </div>
        </div>
        
    </form>
    
@endsection