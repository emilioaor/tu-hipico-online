<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    @yield('meta')
    <title>Sistema Tu Hipico Online</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/sb-admin.css') }}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{ asset('font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    @yield('css')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .notification-marquee {
            position: fixed;
            width: 80%;
            height: 35px;
            z-index: 1031;
            top: 7px;
            left: 20px;
            color: #ccc;
            background-color: #2e2e2e;
            overflow: hidden;
            border-radius: 7px;
        }
        .notification-marquee p {
            position: absolute;
            top: 7px;
            left: 0;
            font-size: 16px;
            overflow: hidden;
            height: 25px;
        }
        @media (max-width: 767px) {
            .top-nav {
                display: none;
            }
        }
    </style>

</head>

<body>

<div class="notification-marquee">
    <p></p>
</div>

<div id="wrapper">

    @include('layout.nav')

    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">

                    @if(Session::has('alert-message') && Session::has('alert-type'))
                        <div class="alert {{ Session::get('alert-type') }} alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                            <ul>
                                <li>{{ Session::get('alert-message') }}</li>
                            </ul>

                        </div>
                    @endif

                    @if($errors->any())

                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                        </div>

                    @endif

                    <h1 class="page-header">
                        @yield('header-title')
                        <small>@yield('header-subtitle')</small>
                    </h1>
                    @yield('current-position')

                    <main>
                        @yield('content')
                    </main>
                </div>
            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="{{ asset('js/jquery.js') }}"></script>

<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
@yield('js')

@if(Auth::check())
    <script>


        var notifications = {!! json_encode(App\Notification::getContentArray()) !!};

        $(window).ready(function() {

            var showNotification = 0;
            var topNotification = notifications.length;
            var velocity = 1000 / 80;
            var movement = 1;
            initNotification();

            var l;
            var topL;

            window.setInterval(function () {

                l += movement;

                if (l >= topL) {
                    showNotification++;

                    if (showNotification > topNotification) {
                        showNotification = 0;
                    }
                    initNotification();
                }

                moveNotificationTo(l);

            }, velocity)


            function initNotification() {
                $('.notification-marquee p').css('display', 'inline-block');
                $('.notification-marquee p').html(notifications[showNotification]);

                l = $('.notification-marquee p').width() * -1 - 20;
                topL = $('.notification-marquee').width();

                moveNotificationTo(l);
            }

            function moveNotificationTo(to) {
                $('.notification-marquee p').css('left', to + 'px');
            }
        });

    </script>
@endif

</body>

</html>