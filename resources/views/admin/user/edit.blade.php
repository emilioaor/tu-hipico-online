@extends('layout.base')

@section('header-title', 'Editar usuario')

@section('header-subtitle', $user->username)

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>  <a href="{{ route('admin.index') }}">Administrador</a> /
            <i class="fa fa-user"></i>  <a href="{{ route('users.index') }}">Usuarios</a> /
            <i class="fa fa-user"></i>  {{ $user->username }}
        </li>
    </ol>
@endsection

@section('content')

    <form action="{{ route('users.update', ['user' => $user->id]) }}" method="post">

        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <div class="row">

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="id">ID de usuario</label>
                    <input type="text"  class="form-control" id="id" value="{{ $user->id }}" placeholder="ID de usuario" disabled>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="username">Nombre de usuario</label>
                    <input type="text"  class="form-control" id="username" value="{{ $user->username }}" placeholder="Nombre de usuario" disabled>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text"  class="form-control" id="name" name="name" value="{{ $user->name }}" placeholder="Nombre" maxlength="50" required>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="top_sale">Tope en ventas <small>(0 sin limite)</small></label>
                    <input type="text"  class="form-control" id="top_sale" name="top_sale" onkeydown="inputMask('#top_sale')" value="{{ $user->top_sale }}" data-mask="000000000000000" placeholder="Tope en ventas" maxlength="15" required>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="print_code">Código de impresión</label>
                    <input type="number"  class="form-control" id="print_code" name="print_code" value="{{ $user->print_code }}" placeholder="Código de impresión" required>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="status">Estatus</label>
                    <input type="text"  class="form-control" id="status" value="{{ $user->status }}" placeholder="Estatus" disabled>
                </div>
            </div>

        </div>

        <div class="form-group">
            <h4>Cambio de contraseña</h4>
        </div>

        <div class="row">

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="newPassword1">Nueva contraseña</label>
                    <input type="password"  class="form-control" id="newPassword1" name="newPassword1" placeholder="Nueva contraseña" maxlength="20">
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="newPassword2">Repetir nueva contraseña</label>
                    <input type="password" class="form-control" id="newPassword2" name="newPassword2" placeholder="Repetir nueva contraseña" maxlength="20">
                </div>
            </div>

        </div>

        <div class="form-group">
            <hr>
            <button class="btn btn-success btn-lg"><i class="fa fa-fw fa-save"></i> Actualizar</button>

            @if($user->status === \App\User::STATUS_ACTIVE)
                <a href="{{ route('users.changeStatus', ['user' => $user->id]) }}" class="btn btn-warning btn-lg"><i class="fa fa-fw fa-arrow-down"></i> Inhabilitar</a>
            @elseif($user->status === \App\User::STATUS_INACTIVE)
                <a href="{{ route('users.changeStatus', ['user' => $user->id]) }}" class="btn btn-primary btn-lg"><i class="fa fa-fw fa-arrow-up"></i> Habilitar</a>
            @endif

            <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#userDeleteModal" onclick="changeActionForm('#userDeleteForm', '{{ route('users.destroy', ['user' => $user->id]) }}')">
                <i class="fa fa-fw fa-remove"></i> Eliminar
            </button>
        </div>

    </form>

    <!-- Modal -->
    <div id="userDeleteModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Atención <i class="fa fa-fw fa-exclamation-triangle"></i></h4>
                </div>
                <div class="modal-body">
                    <p>Al eliminar un usuario ya no podrá acceder al sistema por medio del mismo. Verifique la operación antes de continuar ya que <strong>no se puede deshacer.</strong> ¿Esta seguro de eliminar este usuario?</p>

                    <div class="text-center">
                        <form action="#" id="userDeleteForm" method="post">
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

@section('js')

    <script src="{{ asset('js/plugins/jquery-mask/jquery.mask.min.js') }}"></script>
    <script type="text/javascript">

        function inputMask(id) {

            var re = new RegExp('^0');
            var val = $(id).val();
            var temp;
            var x;

            while (re.test(val)) {

                temp = '';

                for (x = 0; x < val.length; x++) {
                    if (x > 0) temp += val[x];
                }

                val = temp;
            }

            $(id).val(val);
        }

        function changeActionForm(id, url) {
            $(id).attr('action', url);
        }

    </script>

@endsection