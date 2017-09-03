@extends('layout.base')

@section('header-title', 'Lista de usuarios')

@section('header-subtitle')
    <a href="{{ route('users.create') }}" class="btn btn-success"><i class="fa fa-fw fa-plus"></i></a>
@endsection

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>  <a href="{{ route('admin.index') }}">Administrador</a> /
            <i class="fa fa-user"></i>  Usuarios
        </li>
    </ol>
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <form action="{{ route('users.index') }}" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{{ Request::has('search') ? Request::get('search') : '' }}" maxlength="20" placeholder="Busqueda ..." required>
                  <span class="input-group-btn">
                    @if(Request::has('search'))
                          <a href="{{ route('users.index') }}" class="btn btn-default"><i class="glyphicon glyphicon-remove"></i></a>
                      @else
                          <button class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
                      @endif
                  </span>
                </div><!-- /input-group -->
            </form>
        </div><!-- /.col-lg-6 -->
    </div><!-- /.row -->
    <br>

    <table class="table table-responsive">
        <thead>
            <th width="5%"  class="text-center">ID</th>
            <th width="25%">Nombre de usuario</th>
            <th width="25%">Nombre</th>
            <th width="20%">Creado</th>
            <th width="20%">Estatus</th>
            <th width="5%"></th>
        </thead>

        <tbody>
            @foreach($users as $user)
                <tr>
                    <td class="text-center">{{ $user->id }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ date_format($user->created_at, 'd-m-Y') }}</td>
                    <td>
                        <strong>
                            @if($user->status === \App\User::STATUS_ACTIVE)
                                <span class="text-success">{{ $user->status }}</span>
                            @elseif($user->status === App\User::STATUS_INACTIVE)
                                <span class="text-danger">{{ $user->status }}</span>
                            @endif
                        </strong>
                    </td>
                    <td>
                        <a href="{{ route('users.edit', ['user' => $user->id]) }}" class="btn btn-warning"><i class="fa fa-fw fa-edit"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center">
        {{ $users->render() }}
    </div>
@endsection