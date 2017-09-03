<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>

    @if(Auth::check())
        <!-- Top Menu Items -->
        <ul class="nav navbar-right top-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ Auth::user()->name }} <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('user.config') }}"><i class="fa fa-fw fa-gear"></i> Configuración</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="{{ route('index.logout') }}"><i class="fa fa-fw fa-power-off"></i> Salir</a>
                    </li>
                </ul>
            </li>
        </ul>
    @endif

    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav side-nav">
            <li>
                <img src="{{ asset('img/logo.png') }}" id="logo" class="img-responsive" alt="">
            </li>
            @if(Auth::check())

                @if(Auth::user()->level === \App\User::LEVEL_ADMIN)
                    <li>
                        <a href="{{ route('admin.index') }}"><i class="fa fa-fw fa-home"></i> Administrador</a>
                    </li>
                    <li>
                        <a href="{{ route('runs.index') }}"><i class="fa fa-fw fa-road"></i> Carreras</a>
                    </li>
                    <li>
                        <a href="{{ route('horses.index') }}"><i class="glyphicon glyphicon-knight"></i> Caballos</a>
                    </li>
                    <li>
                        <a href="{{ route('hippodromes.index') }}"><i class="glyphicon glyphicon-tower"></i> Hipódromos</a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}"><i class="fa fa-fw fa-user"></i> Usuarios</a>
                    </li>
                @endif

                @if(Auth::user()->level === \App\User::LEVEL_USER)
                    <li>
                        <a href="{{ route('user.index') }}"><i class="fa fa-fw fa-home"></i> Panel principal</a>
                    </li>
                @endif

                    <li>
                        <a href="{{ route('gains.index') }}"><i class="fa fa-fw fa-list-alt"></i> Tickets</a>
                    </li>
                    <li>
                        <a href="{{ route('user.printSpooler') }}"><i class="fa fa-fw fa-print"></i> Cola de impresión</a>
                    </li>
                    <li>
                        <a href="{{ route('user.report.daily') }}"><i class="fa fa-fw fa-file"></i> Reporte del día</a>
                    </li>

            @endif
        </ul>
    </div>
    <!-- /.navbar-collapse -->
</nav>