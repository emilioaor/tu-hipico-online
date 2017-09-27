@extends('layout.base')

@section('header-title', 'Registrar usuario')

@section('header-subtitle', '')

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>  <a href="{{ route('admin.index') }}">Administrador</a> /
            <i class="fa fa-user"></i>  <a href="{{ route('users.index') }}">Usuarios</a> /
            <i class="fa fa-user"></i>  Registrar usuario
        </li>
    </ol>
@endsection

@section('content')

    <form action="{{ route('users.store') }}" method="post">

        {{ csrf_field() }}

        <div class="row">

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="username">Nombre de usuario</label>
                    <input type="text"  class="form-control" id="username" name="username" value="" placeholder="Nombre de usuario" maxlength="20" required>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text"  class="form-control" id="name" name="name" value="" placeholder="Nombre" maxlength="50" required>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="top_sale">Tope en ventas <small>(0 sin limite)</small></label>
                    <input type="text"  class="form-control" id="top_sale" name="top_sale" onkeydown="inputMask('#top_sale')" value="0" data-mask="000000000000000" placeholder="Tope en ventas" maxlength="15" required>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="print_code">Código de impresión</label>
                    <input type="number"  class="form-control" id="print_code" name="print_code" placeholder="Código de impresión" required>
                </div>
            </div>

        </div>

        <div class="form-group">
            <h4>Establecer contraseña</h4>
        </div>

        <div class="row">

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="newPassword1">Nueva contraseña</label>
                    <input type="password"  class="form-control" id="newPassword1" name="newPassword1" placeholder="Nueva contraseña" maxlength="20" required>
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="newPassword2">Repetir nueva contraseña</label>
                    <input type="password" class="form-control" id="newPassword2" name="newPassword2" placeholder="Repetir nueva contraseña" maxlength="20" required>
                </div>
            </div>

        </div>

        <div class="form-group">
            <hr>
            <button class="btn btn-success btn-lg"><i class="fa fa-fw fa-save"></i> Registrar</button>
        </div>

    </form>

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

    </script>

@endsection