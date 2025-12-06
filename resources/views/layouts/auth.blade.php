<!doctype html>
<html lang="ar" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">

<head>

    <meta charset="utf-8" />
    <title> تسجيل دخول | {{ config('app.name', 'CUNTER TRUCK') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta content="ar" name="language" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('/assets/images/rc-favicon.png')}}">

    <!-- Layout config Js -->
    <script src="{{asset('/assets/js/layout.js')}}"></script>
    <link href="{{asset('/css/app.css')}}" rel="stylesheet" type="text/css" />
    <!-- Bootstrap Css -->
    <link href="{{asset('/assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai&family=Lato:wght@700&display=swap" rel="stylesheet">
    @if(App::isLocale('ar'))
    <link href="{{asset('/assets/css/app-rtl.min.css?v=34')}}" rel="stylesheet" type="text/css" />
    @else
    <link href="{{asset('/assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
    @endif
    <!-- custom Css-->
    <link href="{{asset('/assets/css/custom.min.css?v=')}}" rel="stylesheet" type="text/css" />



</head>

<body dir="{{App::isLocale('ar') ? 'rtl' : 'ltr' }}">

    <div class="auth-page-wrapper" id="app">
        @yield('content')

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">
                                {{__('Copyright © 2023CUNTER TRUCK. All rights reserved')}}
                                <br>
                            <small>Development By <a href="https://stech.ly">SafeTech</a></small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <script src="{{asset('/js/app.js')}}"></script>

    <script src="{{asset('/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('/assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('/assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('/assets/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('/assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
    <script src="{{asset('/assets/js/plugins.js')}}"></script>

    <!-- particles js -->
    <script src="{{asset('/assets/libs/particles.js/particles.js')}}"></script>
    <!-- particles app js -->
    <script src="{{asset('/assets/js/pages/particles.app.js')}}"></script>
    <!-- password-addon init -->
    <script src="{{asset('/assets/js/pages/password-addon.init.js')}}"></script>
</body>



</html>