@extends('layout.base')

@section('header-title', 'Editar hipódromo')

@section('header-subtitle', $hippodrome->public_id)

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>  <a href="{{ route('admin.index') }}">Administrador</a> /
            <i class="glyphicon glyphicon-tower"></i>  <a href="{{ route('hippodromes.index') }}">Hipódromos</a> /
            <i class="glyphicon glyphicon-tower"></i>  {{ $hippodrome->public_id }}
        </li>
    </ol>
@endsection

@section('content')

    <form action="{{ route('hippodromes.update', ['hippodrome' => $hippodrome->id]) }}" method="post">

        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <div class="row">

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="public_id">ID publico</label>
                    <input type="text"  class="form-control" id="public_id" value="{{ $hippodrome->public_id }}" placeholder="ID publico" disabled>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="name">Nombre del hipódromo</label>
                    <input type="text"  class="form-control" id="name" name="name" value="{{ $hippodrome->name }}" placeholder="Nombre del hipódromo" required>
                </div>
            </div>

        </div>

        <div class="form-group">
            <hr>
            <button class="btn btn-success btn-lg"><i class="fa fa-fw fa-save"></i> Actualizar</button>

            <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#deleteHippodromeModal" onclick="changeActionForm('#deleteHippodromeForm', '{{ route('hippodromes.destroy', ['hippodrome' => $hippodrome->id]) }}')">
                <i class="fa fa-fw fa-remove"></i> Eliminar
            </button>
        </div>

    </form>

    <!-- Modal -->
    <div id="deleteHippodromeModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Atención <i class="fa fa-fw fa-exclamation-triangle"></i></h4>
                </div>
                <div class="modal-body">
                    <p>Al eliminar un hipódromo no podra asociar carreras al mismo. Verifique la operación antes de continuar ya que <strong>no se puede deshacer.</strong> ¿Esta seguro de eliminar el hipódromo?</p>

                    <div class="text-center">
                        <form action="#" id="deleteHippodromeForm" method="post">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button class="btn btn-danger">SI</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <br>
    <h4>Carreras registradas al hipódromo</h4>

    <div class="row">

        @foreach($hippodrome->runs()->orderBy('date', 'DESC')->get() as $run)
            <div class="col-sm-3">

                <div class="panel panel-default">
                    <div class="panel-body">
                        <p>{{ $run->public_id }}</p>
                        <small>
                            <p><strong>Fecha: </strong> {{ $run->dateInDateTime()->format('d-m-Y') }}</p>

                            @if($run->status === \App\Run::STATUS_OPEN)
                                <p><strong>Estatus: <span class="text-success">{{ $run->status }}</span></strong></p>
                            @elseif($run->status === \App\Run::STATUS_PENDING)
                                <p><strong>Estatus: <span class="text-warning">{{ $run->status }}</span></strong></p>
                            @elseif($run->status === \App\Run::STATUS_CLOSE)
                                <p><strong>Estatus: <span class="text-danger">{{ $run->status }}</span></strong></p>
                            @endif

                            <p><strong>Ganador:</strong> {{ $run->horseGained() ? $run->horseGained()->name : '-' }}</p>
                        </small>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection

@section('js')
    <script type="text/javascript">

        function changeActionForm(id, url) {
            $(id).attr('action', url);
        }

    </script>
@endsection