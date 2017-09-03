@extends('layout.base')

@section('header-title', 'Editar caballo')

@section('header-subtitle', $horse->name)

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>  <a href="{{ route('admin.index') }}">Administrador</a> /
            <i class="glyphicon glyphicon-knight"></i>  <a href="{{ route('horses.index') }}">Caballos</a> /
            <i class="glyphicon glyphicon-knight"></i>  {{ $horse->name }}
        </li>
    </ol>
@endsection

@section('content')

    <form action="{{ route('horses.update', ['horse' => $horse->id]) }}" method="post">

        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <div class="row">

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="public_id">ID publico</label>
                    <input type="text"  class="form-control" id="public_id" value="{{ $horse->public_id }}" placeholder="ID publico" disabled>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text"  class="form-control" id="name" name="name" value="{{ $horse->name }}" placeholder="Nombre" maxlength="50" required>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="status">Estatus</label>
                    <input type="text"  class="form-control" id="status" value="{{ $horse->status }}" placeholder="Estatus" disabled>
                </div>
            </div>

        </div>

        <div class="form-group">
            <hr>
            <button class="btn btn-success btn-lg"><i class="fa fa-fw fa-save"></i> Actualizar</button>

            @if($horse->status === \App\Horse::STATUS_ACTIVE)
                <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#deleteHorseModal">
                    <i class="fa fa-fw fa-remove"></i> Eliminar
                </button>
            @endif
        </div>

    </form>

    <!-- Modal -->
    <div id="deleteHorseModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Atención <i class="fa fa-fw fa-exclamation-triangle"></i></h4>
                </div>
                <div class="modal-body">
                    <p>Al eliminar un caballo ya no lo podrá seleccionar como competidor en las carreras. Verifique la operación antes de continuar ya que <strong>no se puede deshacer.</strong> ¿Esta seguro de eliminar este caballo?</p>

                    <div class="text-center">
                        <form action="{{ route('horses.destroy', ['horse' => $horse->id]) }}" id="deleteHorseForm" method="post">
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
@endsection
