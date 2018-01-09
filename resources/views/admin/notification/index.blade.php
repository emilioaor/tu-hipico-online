@extends('layout.base')

@section('header-title', 'Lista de notificaciones')

@section('current-position')
    <ol class="breadcrumb">
        <li>
            <i class="fa fa-home"></i>  <a href="{{ route('admin.index') }}">Administrador</a> /
            <i class="glyphicon glyphicon-info-sign"></i>  Notificaciones
        </li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-responsive">
                <thead>
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th>Notificación</th>
                        <th width="5%"></th>
                        <th width="5%"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($notifications as $i => $notification)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <form action="{{ route('notifications.update', ['notifications' => $notification->id]) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}

                                <td>
                                    <input type="text" class="form-control" name="content" placeholder="Notificación" value="{{ $notification->content }}" required>
                                </td>
                                <td>
                                    <button class="btn btn-warning">
                                        <i class="glyphicon glyphicon-edit"></i>
                                    </button>
                                </td>
                            </form>
                            <td>
                                <form action="{{ route('notifications.destroy', ['notifications' => $notification->id]) }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                    <button class="btn btn-danger" onclick="return confirm('¿Seguro quiere eliminar esta notificación?')">
                                        <i class="glyphicon glyphicon-remove"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    <form action="{{ route('notifications.store') }}" method="post">
                        {{ csrf_field() }}
                        <tr>
                            <td class="text-center">?</td>
                            <td>
                                <input type="text" class="form-control" name="content" placeholder="Notificación" required>
                            </td>
                            <td>
                                <button class="btn btn-success">
                                    <i class="glyphicon glyphicon-plus"></i>
                                </button>
                            </td>
                            <td></td>
                        </tr>
                    </form>
                </tbody>
            </table>
        </div>
    </div>
@endsection