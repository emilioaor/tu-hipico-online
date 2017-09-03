@extends('layout.base')

@section('header-title', 'Administrador')

@section('header-subtitle', Auth::user()->name)

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>  <a href="{{ route('admin.index') }}">Administrador</a>
        </li>
    </ol>
@endsection

@section('content')

    <div class="row">

        <div class="col-sm-6">

            <div class="panel panel-default">
                <div class="panel-heading">Carreras registradas para hoy {{ date('d-m-Y') }}</div>
                <div class="panel-body">

                    <table class="table table-responsive table-striped">
                        <thead>
                            <th width="50%">Carrera</th>
                            <th width="30%">Estatus</th>
                            <th width="20%" class="text-center">En vivo</th>
                        </thead>
                        <tbody>
                        @foreach($runs as $run)
                            <form action="{{ route('runs.changeStatus', ['id' => $run->id]) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                <tr>
                                    <td>{{ $run->public_id }}</td>

                                    @if($run->status === \App\Run::STATUS_PENDING)
                                        <td class="text-warning"><strong>{{ $run->status }}</strong></td>
                                    @elseif($run->status === \App\Run::STATUS_OPEN)
                                        <td class="text-success"><strong>{{ $run->status }}</strong></td>
                                    @elseif($run->status === \App\Run::STATUS_CLOSE)
                                        <td class="text-danger"><strong>{{ $run->status }}</strong></td>
                                    @endif

                                    <td class="text-center">
                                        <a href="{{ route('runs.show', ['run' => $run->id]) }}" class="btn btn-primary"><i class="fa fa-fw fa-eye"></i></a>
                                    </td>
                                </tr>
                            </form>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>

    </div>

@endsection