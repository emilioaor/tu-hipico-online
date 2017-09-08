@extends('layout.base')

@section('header-title', 'Usuario')

@section('header-subtitle', Auth::user()->name)

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>  <a href="{{ route('user.index') }}">Usuario</a>
        </li>
    </ol>
@endsection

@section('content')

    <div class="row">

        <div class="col-sm-6">

            <div class="panel panel-default">
                <div class="panel-heading">Carrera activa actualmente</div>
                <div class="panel-body">

                    @if($activeRun)

                        <label for="">ID publico</label>
                        <p>{{ $activeRun->public_id }}</p>

                        <label for="">Caballos registrados</label>
                        @foreach($activeRun->orderedHorses() as $horse)
                            <p>{{ $horse->public_id . ' - ' . $horse->name }}</p>
                        @endforeach

                        <a href="{{ route('gains.create') }}" class="btn btn-success"><i class="fa fa-fw fa-plus"></i>Agregar ticket (F4)</a>

                    @else
                        <p>En este momento todas las carreras estan cerradas. Solo podrá registrar tickets mientras la carrera este abierta.</p>
                    @endif

                </div>
            </div>

        </div>

        <div class="col-sm-6">

            <div class="panel panel-default">
                <div class="panel-heading">Carreras registradas para hoy {{ date('d-m-Y') }}</div>
                <div class="panel-body">

                    <table class="table table-responsive table-striped">
                        <thead>
                        <th width="70%">ID publico</th>
                        <th width="30%">Estatus</th>
                        </thead>
                        <tbody>
                        @foreach($runs as $run)
                            <tr>
                                <td>{{ $run->public_id }}</td>
                                <td>
                                    @if($run->status === \App\Run::STATUS_PENDING)
                                        <strong><span class="text-warning">{{ $run->status }}</span></strong>
                                    @elseif($run->status === \App\Run::STATUS_OPEN)
                                        <strong><span class="text-success">{{ $run->status }}</span></strong>
                                    @elseif($run->status === \App\Run::STATUS_CLOSE)
                                        <strong><span class="text-danger">{{ $run->status }}</span></strong>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>

    </div>

@endsection

@section('js')
    <script type="text/javascript">

        $(document).ready(function () {

            $(document).keydown(function (evt) {
                if (evt.keyCode === 115) {
                    location.href = '{{ route('gains.create') }}';
                }
            });
        });
    </script>
@endsection