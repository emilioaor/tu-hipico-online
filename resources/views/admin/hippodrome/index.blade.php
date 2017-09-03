@extends('layout.base')

@section('header-title', 'Lista de hipódromos')

@section('header-subtitle')
    <a href="{{ route('hippodromes.create') }}" class="btn btn-success"><i class="fa fa-fw fa-plus"></i></a>
@endsection

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>  <a href="{{ route('admin.index') }}">Administrador</a> /
            <i class="glyphicon glyphicon-tower"></i>  Hipódromos
        </li>
    </ol>
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <form action="{{ route('hippodromes.index') }}" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{{ Request::has('search') ? Request::get('search') : '' }}" maxlength="20" placeholder="Busqueda ..." required>
                  <span class="input-group-btn">
                    @if(Request::has('search'))
                          <a href="{{ route('hippodromes.index') }}" class="btn btn-default"><i class="glyphicon glyphicon-remove"></i></a>
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
            <th width="45%">ID Publico</th>
            <th width="45%">Nombre</th>
            <th width="5%" class="text-center"></th>
        </thead>

        <tbody>
            @foreach($hippodromes as $hippodrome)
                <tr>
                    <td class="text-center">{{ $hippodrome->id }}</td>
                    <td>{{ $hippodrome->public_id }}</td>
                    <td>{{ $hippodrome->name }}</td>
                    <td>
                        <a href="{{ route('hippodromes.edit', ['hippodrome' => $hippodrome->id]) }}" class="btn btn-warning"><i class="fa fa-fw fa-edit"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center">
        {{ $hippodromes->render() }}
    </div>
@endsection