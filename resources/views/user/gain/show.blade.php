@extends('layout.base')

@section('header-title', 'Detalle de ticket')

@section('header-subtitle', $ticket->public_id)

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-list-alt"></i>  <a href="{{ route('gains.index') }}">Tickets</a> /
            <i class="fa fa-list-alt"></i>  Detalle de ticket
        </li>
    </ol>
@endsection

@section('content')

    <section class="print-hide">

        <div class="row">

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="">Hipódromo</label>
                    <p>{{ $ticket->run->hippodrome->name }}</p>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="">Carrera</label>
                    <p>{{ $ticket->run->public_id }}</p>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="">Estatus</label>
                    @if($ticket->status === \App\Ticket::STATUS_ACTIVE)
                        <p class="text-success"><strong>{{ $ticket->status }}</strong></p>
                    @elseif($ticket->status === \App\Ticket::STATUS_NULL)
                        <p class="text-danger"><strong>{{ $ticket->status }}</strong></p>
                    @elseif($ticket->status === \App\Ticket::STATUS_PAY)
                        <p class="text-success"><strong>{{ $ticket->status }}</strong></p>
                    @endif
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="">Nota</label>
                    <p>{{ $ticket->note }}</p>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="">Monto a pagar</label>
                    <p>{{ number_format($ticket->payAmount(), 2, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-xs-12">
                <table class="table table-responsive table-striped">
                    <thead>
                    <tr>
                        <th width="25%">Detalle de la apuesta</th>
                        <th width="10%" class="text-center">Estatus</th>
                        <th width="10%" class="text-center">Precio / Tabla</th>
                        <th width="10%" class="text-center">Tablas</th>
                        <th width="15%" class="text-center">Total tablas</th>
                        <th width="15%" class="text-center">Ganador</th>
                        <th width="15%" class="text-center">Ganador + Tabla</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ticket->ticketDetails as $detail)
                        <tr>
                            <td>{{ $detail->horse->public_id . ' - ' . $detail->horse->name }}</td>
                            <td class="text-center">
                                @if($detail->status === \App\TicketDetail::STATUS_ACTIVE)
                                    <strong><span class="text-success">{{ $detail->status }}</span></strong>
                                @elseif($detail->status === \App\TicketDetail::STATUS_NULL)
                                    <strong><span class="text-danger">{{ $detail->status }}</span></strong>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($detail->status === App\TicketDetail::STATUS_ACTIVE)
                                    {{ $price = $detail->horse->runs()->find($ticket->run_id)->pivot->static_table }}
                                @elseif($detail->status === \App\TicketDetail::STATUS_NULL)
                                    <del>{{ $price = $detail->horse->runs()->find($ticket->run_id)->pivot->static_table }}</del>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($detail->status === App\TicketDetail::STATUS_ACTIVE)
                                    {{ $detail->tables }}
                                @elseif($detail->status === \App\TicketDetail::STATUS_NULL)
                                    <del>{{ $detail->tables }}</del>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($detail->status === App\TicketDetail::STATUS_ACTIVE)
                                    {{ $price * $detail->tables }}
                                @elseif($detail->status === \App\TicketDetail::STATUS_NULL)
                                    <del>{{ $price * $detail->tables }}</del>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($detail->status === App\TicketDetail::STATUS_ACTIVE)
                                    {{ $detail->gain_amount }}
                                @elseif($detail->status === \App\TicketDetail::STATUS_NULL)
                                    <del>{{ $detail->gain_amount }}</del>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($detail->status === App\TicketDetail::STATUS_ACTIVE)
                                    {{ $price * $detail->tables + $detail->gain_amount }}
                                @elseif($detail->status === \App\TicketDetail::STATUS_NULL)
                                    <del>{{ $detail->gain_amount }}</del>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Total</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="text-center">{{ $ticket->totalActiveAmount() }}</th>
                    </tr>
                    </tfoot>
                </table>

            </div>

        </div>

        <div class="form-group">
            <hr>
            <a href="{{ route('gains.create') }}" class="btn btn-success btn-lg"><i class="fa fa-fw fa-plus"></i> Nuevo ticket</a>

            @if(\App\Ticket::STATUS_ACTIVE === $ticket->status)
                <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#myModal">
                    <i class="fa fa-fw fa-remove"></i> Anular ticket
                </button>
            @endif

            @if($ticket->status === \App\Ticket::STATUS_ACTIVE && $ticket->isGain())
                <form
                    action="{{ route('gains.payTicket', ['gain' => $ticket->id]) }}"
                    method="post"
                    style="display: inline-block;">

                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <button class="btn btn-primary btn-lg">
                            <i class="fa fa-fw fa-money"></i> Pagar ticket
                        </button>
                </form>
            @endif

            @if(! $ticket->isPrintSpooler())
                <form action="{{ route('gains.printTicket', ['ticket' => $ticket->id]) }}" id="formPrint" method="post" style="display: inline-block">
                    {{ csrf_field() }}
                    <button class="btn btn-primary btn-lg"><i class="fa fa-fw fa-print"></i> Imprimir (F2)</button>
                </form>
            @endif
        </div>

        <!-- Modal -->
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Atención <i class="fa fa-fw fa-exclamation-triangle"></i></h4>
                    </div>
                    <div class="modal-body">
                        <p>Al anular el ticket se invalida todas las apuestas referente al mismo. Verifique la operación antes de continuar ya que <strong>no se puede deshacer.</strong> ¿Esta seguro de anular el ticket?</p>

                        <form action="{{ route('gains.destroy', ['gain' => $ticket->id]) }}" method="post">

                            <div class="form-group">
                                <textarea name="note" id="note" class="form-control" maxlength="255" cols="30" rows="5"></textarea>
                            </div>

                            <div class="text-center">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button class="btn btn-danger">SI</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>

    </section>

    <section class="print-show hidden">
        @include('user.gain.printTicket')
    </section>

@endsection

@section('js')

    <script type="text/javascript">

        $(document).ready(function () {

            $(document).keydown(function (evt) {
                if (evt.keyCode === 113) {
                    //window.open('{{ route('gains.downloadTicket', ['gain' => $ticket->id]) }}', '_blank');
                    printTicket();
                }
            });


            /*$('#btnPrint').on('click', function() {

                $('#ticketPrint').focus()
                        .get(0).contentWindow.print()
                ;
            });*/
        });


        function printTicket() {
            $('#formPrint').submit();
        }
    </script>
@endsection