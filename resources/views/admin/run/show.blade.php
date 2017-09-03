@extends('layout.base')

@section('header-title', 'Vista en vivo')

@section('header-subtitle', $run->public_id)

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>  <a href="{{ route('admin.index') }}">Administrador</a> /
            <i class="fa fa-road"></i>  Vista en vivo
        </li>
    </ol>
@endsection

@section('content')

    <div class="row">

        <div class="col-sm-4">
            <div class="form-group">
                <label for="">ID publico</label>
                <p>{{ $run->public_id }}</p>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="">Fecha</label>
                <p>{{ date_format($run->dateInDateTime(), 'd-m-Y') }}</p>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="form-group">
                <label for="">Estatus</label>

                @if($run->status === \App\Run::STATUS_PENDING)
                    <p class="text-warning"><strong>{{ $run->status }}</strong></p>
                @elseif($run->status === \App\Run::STATUS_OPEN)
                    <p class="text-success"><strong>{{ $run->status }}</strong></p>
                @elseif($run->status === \App\Run::STATUS_CLOSE)
                    <p class="text-danger"><strong>{{ $run->status }}</strong></p>
                @endif

            </div>
        </div>

    </div>

    <h4>Caballos registrados a esta carrera</h4>

    <form action="{{ route('runs.updateTable', ['run' => $run->id]) }}" method="post">

        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <div class="row">

            @foreach($run->horses as $horse)
                <div class="col-sm-4">

                    <div class="panel panel-default">
                        <div class="panel-body">

                            <div class="row">

                                <div class="col-xs-8">
                                    <p>
                                        {{ $horse->public_id . ' - ' . $horse->name }}
                                    </p>
                                    <p>
                                        <strong>
                                            Estatus:

                                            @if($horse->pivot->isGain)

                                                <span class="text-success">Ganador</span>
                                            @else

                                                @if($horse->pivot->status === \App\Horse::STATUS_ACTIVE)
                                                    <span class="text-primary">{{ $horse->pivot->status }}</span>
                                                @elseif($horse->pivot->status === \App\Horse::STATUS_RETIRED)
                                                    <span class="text-danger">{{ $horse->pivot->status }}</span>
                                                @endif

                                            @endif
                                        </strong>
                                    </p>
                                    <p>
                                        <label>Precio por tabla</label>
                                        <input
                                                type="number"
                                                name="table[{{ $horse->id }}]"
                                                value="{{ $horse->pivot->static_table }}"
                                                class="form-control"
                                                min="0"
                                                {{ $run->status === \App\Run::STATUS_CLOSE || $horse->pivot->status === \App\Horse::STATUS_RETIRED  ? 'disabled' : '' }}
                                        >
                                    </p>
                                </div>

                                <div class="col-xs-4 text-right">

                                    @if($run->status !== \App\Run::STATUS_PENDING && $horse->pivot->status === \App\Horse::STATUS_ACTIVE)

                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="glyphicon glyphicon-cog"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a
                                                            href="Javascript"
                                                            onclick="changeActionForm('#retireHorseForm', '{{ route('runs.retireHorse', ['run' => $run->id, 'horse' => $horse->id]) }}')"
                                                            data-toggle="modal"
                                                            data-target="#retireHorseModal">
                                                        <i class="fa fa-fw fa-remove"></i> Retirar
                                                    </a>
                                                </li>

                                                @if($run->status === \App\Run::STATUS_CLOSE)
                                                    <li>
                                                        <a href="{{ route('runs.setGained', ['run' => $run->id, 'horse' => $horse->id]) }}"><i class="fa fa-fw fa-star"></i> Ganador</a>
                                                    </li>
                                                @endif

                                            </ul>
                                        </div>

                                    @endif

                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            @endforeach

        </div>

        @if($run->status === \App\Run::STATUS_PENDING)
            <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#openRunModal" onclick="changeActionForm('#openRunForm', '{{ route('runs.changeStatus', ['run' => $run->id]) }}')">
                <i class="fa fa-fw fa-arrow-up"></i> Abrir carrera
            </button>
            <a href="{{ route('runs.edit', ['run' => $run->id]) }}" class="btn btn-warning btn-lg"><i class="fa fa-fw fa-edit"></i> Editar carrera</a>
        @elseif($run->status === \App\Run::STATUS_OPEN)
            <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#closeRunModal" onclick="changeActionForm('#closeRunForm', '{{ route('runs.changeStatus', ['run' => $run->id]) }}')">
                <i class="fa fa-fw fa-arrow-down"></i> Cerrar carrera
            </button>
        @endif

        @if($run->status !== \App\Run::STATUS_CLOSE)
            <button class="btn btn-primary btn-lg">
                <i class="fa fa-fw fa-save"></i> Actualizar tablas
            </button>
        @endif

        <hr>
        <h4>Tickets registrados para esta carrera</h4>
        <p>Vista en tiempo real de los tickets que se registran a esta carrera. Actualiza cada 5 segundos</p>

        <table class="table table-responsive table-striped">
            <thead>
                <th>ID publico</th>
                <th>Taquilla</th>
                <th>Monto</th>
            </thead>
            <tbody id="spaceTickets">
                @foreach($run->tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->public_id }}</td>
                        <td>{{ $ticket->user->name }}</td>
                        <td>{{ $ticket->totalActiveAmount() }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfooter>
                <th>Total</th>
                <th></th>
                <th id="spaceTotalAmount">{{ $run->totalTicketsAmount() }}</th>
            </tfooter>
        </table>

    </form>

    <!-- Modal -->
    <div id="retireHorseModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Atención <i class="fa fa-fw fa-exclamation-triangle"></i></h4>
                </div>
                <div class="modal-body">
                    <p>Al retirar un caballo de ultima hora el mismo seguirá registrado a la carrera pero con un estatus "Retirado". Verifique la operación antes de continuar ya que <strong>no se puede deshacer.</strong> ¿Esta seguro de retirar este caballo?</p>

                    <div class="text-center">
                        <form action="#" id="retireHorseForm" method="post">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <button class="btn btn-danger">SI</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div id="openRunModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Atención <i class="fa fa-fw fa-exclamation-triangle"></i></h4>
                </div>
                <div class="modal-body">
                    <p>Al abrir la carrera permitirá en taquilla registrar apuestas asociadas a la misma. Verifique la operación antes de continuar ya que <strong>no se puede deshacer.</strong> ¿Esta seguro de abrir esta carrera?</p>

                    <div class="text-center">
                        <form action="#" id="openRunForm" method="post">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <button class="btn btn-danger">SI</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div id="closeRunModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Atención <i class="fa fa-fw fa-exclamation-triangle"></i></h4>
                </div>
                <div class="modal-body">
                    <p>Al cerrar esta carrera se bloqueará la creación de apuestas asociadas a la misma. Verifique la operación antes de continuar ya que <strong>no se puede deshacer.</strong> ¿Esta seguro de cerrar esta carrera?</p>

                    <div class="text-center">
                        <form action="#" id="closeRunForm" method="post">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <button class="btn btn-danger">SI</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript">

        var ajaxControl = true;

        $(window).on('load', function () {
            window.setInterval(getTickets, 5000);
        });

        function changeActionForm(id, url) {
            $(id).attr('action', url);
        }

        function getTickets() {

            if (ajaxControl) {

                $.ajax({
                    url : '{{ route('runs.tickets.rest', ['run' => $run->id]) }}',
                    beforeSend : function () {
                        ajaxControl = false;
                    },
                    success : function (data) {
                        renderResponse(data);
                    },
                    error : function (error) {
                        console.log(error);
                    },
                    complete : function () {
                        ajaxControl = true;
                    }
                });
            }
        }

        function renderResponse(data) {
            var html = '';
            var total = 0;

            for (var x = 0; x < data.data.length; x++) {

                total += data.data[x].amount;

                html += '<tr>';
                html +=     '<td>' + data.data[x].public_id + '</td>';
                html +=     '<td>' + data.data[x].user + '</td>';
                html +=     '<td>' + data.data[x].amount + '</td>';
                html += '</tr>';
            }

            $('#spaceTickets').html(html);
            $('#spaceTotalAmount').html(total);
        }

    </script>

@endsection