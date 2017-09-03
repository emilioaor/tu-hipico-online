@extends('layout.base')

@section('header-title', 'Lista de carreras')

@section('header-subtitle')
    <a href="{{ route('runs.create') }}" class="btn btn-success"><i class="fa fa-fw fa-plus"></i></a>
@endsection

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-fw fa-home"></i>  <a href="{{ route('admin.index') }}">Administrador</a> /
            <i class="fa fa-fw fa-road"></i>  Carreras
        </li>
    </ol>
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <form action="{{ route('runs.index') }}" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{{ Request::has('search') ? Request::get('search') : '' }}" maxlength="20" placeholder="Busqueda ..." required>
                  <span class="input-group-btn">
                    @if(Request::has('search'))
                          <a href="{{ route('runs.index') }}" class="btn btn-default"><i class="glyphicon glyphicon-remove"></i></a>
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
            <th width="20%">ID Publico</th>
            <th width="20%">Fecha</th>
            <th width="20%">Estatus</th>
            <th width="20%">Ganador</th>
            <th width="7%" class="text-center"></th>
            <th width="8%" class="text-center"></th>
        </thead>

        <tbody>
            @foreach($runs as $run)
                <tr id="row{{ $run->id }}">
                    <td class="text-center">{{ $run->id }}</td>
                    <td>{{ $run->public_id }}</td>
                    <td>{{ date_format($run->dateInDateTime(), 'd-m-Y') }}</td>
                    <td>
                        @if($run->status === \App\Run::STATUS_PENDING)
                            <strong><span class="text-warning">{{ $run->status }}</span></strong>
                        @elseif($run->status === \App\Run::STATUS_CLOSE)
                            <strong><span class="text-danger">{{ $run->status }}</span></strong>
                        @elseif($run->status === \App\Run::STATUS_OPEN)
                            <strong><span class="text-success">{{ $run->status }}</span></strong>
                        @endif
                    </td>
                    <td>{{ $run->horseGained() ? $run->horseGained()->name : '' }}</td>
                    <td class="text-center">
                        @if($run->status === \App\Run::STATUS_PENDING)
                            <a href="{{ route('runs.edit', ['run' => $run->id]) }}" class="btn btn-warning"><i class="fa fa-fw fa-edit"></i></a>
                        @else
                            <button class="btn btn-warning" disabled><i class="fa fa-fw fa-edit"></i></button>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('runs.show', ['run' => $run->id]) }}" class="btn btn-primary"><i class="fa fa-fw fa-eye"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center">
        {{ $runs->render() }}
    </div>
@endsection
