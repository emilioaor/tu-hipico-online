@extends('layout.base')

@section('header-title', 'Lista de caballos')

@section('header-subtitle')
    <a href="{{ route('horses.create') }}" class="btn btn-success"><i class="fa fa-fw fa-plus"></i></a>
@endsection

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>  <a href="{{ route('admin.index') }}">Administrador</a> /
            <i class="glyphicon glyphicon-knight"></i>  Caballos
        </li>
    </ol>
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <form action="{{ route('horses.index') }}" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{{ Request::has('search') ? Request::get('search') : '' }}" maxlength="20" placeholder="Busqueda ..." required>
                  <span class="input-group-btn">
                    @if(Request::has('search'))
                          <a href="{{ route('horses.index') }}" class="btn btn-default"><i class="glyphicon glyphicon-remove"></i></a>
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
            <th width="5%" class="text-center">ID</th>
            <th width="30%">ID Publico</th>
            <th width="40%">Nombre</th>
            <th width="20%">Estatus</th>
            <th width="5%"></th>
        </thead>

        <tbody>
            @foreach($horses as $horse)
                <tr>
                    <td class="text-center">{{ $horse->id }}</td>
                    <td>{{ $horse->public_id }}</td>
                    <td>{{ $horse->name }}</td>
                    <td>
                        @if($horse->status === \App\Horse::STATUS_ACTIVE)
                            <strong><span class="text-success">{{ $horse->status }}</span></strong>
                        @elseif($horse->status === \App\Horse::STATUS_DELETED)
                            <strong><span class="text-danger">{{ $horse->status }}</span></strong>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('horses.edit', ['horse' => $horse->id]) }}" class="btn btn-warning"><i class="fa fa-fw fa-edit"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center">
        {{ $horses->render() }}
    </div>
@endsection