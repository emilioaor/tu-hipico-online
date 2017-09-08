@extends('layout.base')

@section('header-title', 'Registrar ticket')

@section('header-subtitle', '')

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-list-alt"></i>  <a href="{{ route('gains.index') }}">Tickets</a> /
            <i class="fa fa-list-alt"></i>  Registrar ticket
        </li>
    </ol>
@endsection

@section('content')

    <form action="{{ route('gains.store') }}" method="post">

        {{ csrf_field() }}
        <input type="hidden" name="countRetired" value="{{ count($retiredHorses) }}">

        <div class="row">

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="username">Carrera activa</label>
                    <input type="hidden" value="{{ $activeRun->id }}" name="run_id">
                    <input type="text" value="{{ $activeRun->public_id }}" class="form-control" disabled>
                </div>
            </div>

        </div>

        <div id="valueInTableAlert" class="alert alert-success alert-dismissible hidden" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <p>
                <strong>Atención: </strong> se ha actualizado el valor en tablas
            </p>

        </div>

        <div class="row">

            <div class="col-xs-12">

                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th width="25%">Caballo</th>
                            <th width="10%" class="text-center">Estatus</th>
                            <th width="10%" class="text-center">Precio / Tabla</th>
                            <th width="10%" class="text-center">Tablas</th>
                            <th width="15%" class="text-center">Total tablas</th>
                            <th width="15%" class="text-center">Ganador</th>
                            <th width="15%" class="text-center">Ganador + Tabla</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activeRun->orderedHorses() as $horse)
                            <tr>
                                <td>{{ $horse->public_id . ' - ' . $horse->name }}</td>
                                <td class="text-center">
                                    @if( in_array($horse->id, $retiredHorses) )
                                        <strong><span class="text-danger">Retirado</span></strong>
                                    @else
                                        <strong><span class="text-success">Activo</span></strong>
                                    @endif
                                </td>
                                <td class="text-center" id="spaceValueInTable{{ $horse->id }}">
                                    @if( in_array($horse->id, $retiredHorses) )
                                        <del><span id="valueInTableText{{ $horse->id }}">{{ number_format($horse->pivot->static_table, 2, ',', '.') }}</span></del>
                                    @else
                                        <span id="valueInTableText{{ $horse->id }}">{{ number_format($horse->pivot->static_table, 2, ',', '.') }}</span>
                                    @endif
                                    <input type="hidden" id="valueInTable{{ $horse->id }}" value="{{ $horse->pivot->static_table }}">
                                </td>
                                <td class="text-center">
                                    <input
                                        type="number"
                                        class="form-control"
                                        name="table[{{ $horse->id }}]"
                                        id="table{{ $horse->id }}"
                                        min="0"
                                        value="0"
                                        placeholder="Tablas a jugar"
                                        data-base="{{ $horse->pivot->static_table }}"
                                        onclick="updateTotal({{ $horse->id }})"
                                        onkeyup="updateTotal({{ $horse->id }})"
                                        {{ in_array($horse->id, $retiredHorses) || empty($horse->pivot->static_table) ? 'disabled' : '' }}
                                    >
                                </td>
                                <td class="text-center" id="spaceTotalTable{{ $horse->id }}">
                                    @if( in_array($horse->id, $retiredHorses) )
                                        <del>{{ number_format(0, 2, ',', '.') }}</del>
                                    @else
                                        {{ number_format(0, 2, ',', '.') }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    <input
                                        type="number"
                                        class="form-control"
                                        name="gain[{{ $horse->id }}]"
                                        id="gain{{ $horse->id }}"
                                        min="0"
                                        value="0"
                                        placeholder="Tablas a jugar"
                                        data-base="{{ $horse->pivot->static_table }}"
                                        onclick="updateTotal({{ $horse->id }})"
                                        onkeyup="updateTotal({{ $horse->id }})"
                                        {{ in_array($horse->id, $retiredHorses) ? 'disabled' : '' }}
                                    >
                                </td>
                                <td class="text-center" id="spaceTotal{{ $horse->id }}">
                                    @if( in_array($horse->id, $retiredHorses) )
                                        <del>{{ number_format(0, 2, ',', '.') }}</del>
                                    @else
                                        {{ number_format(0, 2, ',', '.') }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>

        <div class="form-group">
            <hr>
            <button class="btn btn-success btn-lg"><i class="fa fa-fw fa-save"></i> Registrar</button>
        </div>

    </form>

@endsection

@section('js')
    <script type="text/javascript">

        $(window).on('load', function() {
            setInterval(verifyChangeInTables, 5000);
        });

        function updateTotal(id) {

            var amountTable = parseInt($('#table' + id).data('base')) * parseInt($('#table' + id).val());
            var amountGain = $('#gain' + id).val() !== '' ? $('#gain' + id).val() : 0;

            var total = parseInt(amountTable) + parseInt(amountGain);

            $('#spaceTotalTable' + id).html(amountTable);
            $('#spaceTotal' + id).html(total);
        }

        var canAjax = true;

        function verifyChangeInTables() {
            //  Verifica cada 5 segundos si existen cambios en las tablas

            if (canAjax) {

                $.ajax({
                    url : '{{ route('gains.valueInTables.rest') }}',
                    beforeSend : function () {
                        canAjax = false
                    },
                    success : function (data) {
                        verifyTableChanges(data.data);
                    },
                    error : function (error) {
                        console.log(error);
                    },
                    complete : function () {
                        canAjax = true
                    }
                });
            }
        }


        function verifyTableChanges(horses) {

            var valueInTable;
            var haveChanges = false;

            for (var x = 0; x < horses.length; x++) {

                valueInTable = parseInt($('#valueInTable' + horses[x].horseId).val());

                if (valueInTable !== horses[x].static_table) {

                    $('#valueInTable' + horses[x].horseId).val(horses[x].static_table);
                    $('#valueInTableText' + horses[x].horseId).html(horses[x].static_table);
                    $('#spaceValueInTable' + horses[x].horseId).css('background-color', '#5cb85c');
                    $('#valueInTableAlert').removeClass('hidden');
                    $('#table' + horses[x].horseId)
                            .data('base', horses[x].static_table)
                            .val('0')
                    ;
                    updateTotal(horses[x].horseId);

                    if (horses[x].static_table === 0) {
                        $('#table' + horses[x].horseId).attr('disabled', 'disabled');
                    } else {
                        $('#table' + horses[x].horseId).removeAttr('disabled');
                    }

                    haveChanges = true;
                }
            }

            if (haveChanges) {
                // Open modal
            }
        }

    </script>
@endsection