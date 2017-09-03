@extends('layout.base')

@section('header-title', 'Tickets')

@section('header-subtitle')
    <a href="{{ route('gains.create') }}" class="btn btn-success"><i class="fa fa-fw fa-plus"></i></a>
@endsection

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-list-alt"></i>  <a href="{{ route('gains.index') }}">Tickets</a>
        </li>
    </ol>
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <form action="{{ route('gains.index') }}" method="get">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{{ Request::has('search') ? Request::get('search') : '' }}" maxlength="20" placeholder="Busqueda ..." required>
                  <span class="input-group-btn">
                    @if(Request::has('search'))
                          <a href="{{ route('gains.index') }}" class="btn btn-default"><i class="glyphicon glyphicon-remove"></i></a>
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
            <th width="25%">Ultimos tickets</th>
            <th width="15%">Estatus</th>
            <th width="20%">Creado</th>
            <th width="20%">Carrera</th>
            <th width="15%">Monto</th>
            <th width="5%"></th>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->public_id }}</td>
                    <td>
                        @if($ticket->status === \App\Ticket::STATUS_ACTIVE)
                            <strong><span class="text-success">{{ $ticket->status }}</span></strong>
                        @else
                            <strong><span class="text-danger">{{ $ticket->status }}</span></strong>
                        @endif
                    </td>
                    <td>{{ date_format($ticket->created_at, 'd-m-Y') }}</td>
                    <td>{{ $ticket->run->public_id }}</td>
                    <td>{{ $ticket->totalActiveAmount() }}</td>
                    <td>
                        <a href="{{ route('gains.show', ['gain' => $ticket->id]) }}" class="btn btn-warning"><i class="fa fa-fw fa-eye"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center">
        {{ $tickets->render() }}
    </div>

@endsection