@extends('layout.base')

@section('header-title', 'Cola de impresión')

@section('header-subtitle')
    {{ Auth::user()->username }}
@endsection

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-print"></i>  <a href="{{ route('user.printSpooler') }}">Cola de impresión</a>
        </li>
    </ol>
@endsection

@section('content')

    <table class="table table-responsive">
        <thead>
            <tr>
                <th width="30%">Ticket</th>
                <th width="20%">Fecha registro</th>
                <th width="20%">Fecha impresión</th>
                <th width="15%">Taquilla</th>
                <th width="15%">Estatus</th>
            </tr>
        </thead>
        <tbody>
            @foreach($printSpooler as $print)
                <tr>
                    <td><a href="{{ route('gains.show', ['gain' => $print->ticket->id]) }}">{{ $print->ticket->public_id }}</a></td>
                    <td>{{ date_format($print->created_at, 'd-m-Y h:i a') }}</td>
                    <td>
                        @if($print->status === \App\PrintSpooler::STATUS_COMPLETE)
                            {{ date_format($print->updated_at, 'd-m-Y h:i a') }}
                        @endif
                    </td>
                    <td>{{ $print->ticket->user->username }}</td>
                    <td>
                        @if($print->status == \App\PrintSpooler::STATUS_PENDING)
                            <strong>
                                <span class="text-warning">
                                    {{ $print->status }}
                                </span>
                            </strong>
                        @elseif($print->status == \App\PrintSpooler::STATUS_COMPLETE)
                            <strong>
                                <span class="text-success">
                                    {{ $print->status }}
                                </span>
                            </strong>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-center">
        {{ $printSpooler->render() }}
    </div>

@endsection