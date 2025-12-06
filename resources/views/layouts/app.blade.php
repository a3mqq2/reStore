<!doctype html>
<html lang="ar" dir="rtl" data-layout="vertical" data-topbar="dark" data-sidebar="dark" data-sidebar-size="lg">


<head>

    <meta charset="utf-8" />
    <title>@yield('title') |  re-store</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="CUNTER TRUCK" name="description" />
    <meta content="HULUL IT -- hulul.ly" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.svg">
    <meta name="google" content="notranslate">

    <!-- Layout config Js -->
    <script src="{{asset('/assets/js/layout.js')}}"></script>
    <link href="{{asset('/css/app.css?v=34')}}" rel="stylesheet" type="text/css" />
   
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

    <!-- Bootstrap Css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/dropzone.min.css">
    <link href="{{asset('/assets/css/bootstrap-rtl.min.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.5/css/selectize.bootstrap5.min.css" integrity="sha512-w4sRMMxzHUVAyYk5ozDG+OAyOJqWAA+9sySOBWxiltj63A8co6YMESLeucKwQ5Sv7G4wycDPOmlHxkOhPW7LRg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Icons Css -->
    <link href="{{asset('/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('/assets/css/app-rtl.min.css?v=34')}}" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdn.ckeditor.com/4.17.2/standard-all/translations/ar.js"></script>
    <script src="https://cdn.ckeditor.com/4.24.0/standard/ckeditor.js"></script>
    
    <style>
        .cke_editable {
            font-family: 'Amiri', serif;
        }
    </style>



    @yield('styles')
    <link href="{{asset('/assets/css/custom.min.css?v=')}}" rel="stylesheet" type="text/css" />
    @include('layouts.styles')
    <meta content="ar" name="language" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        let csrfToken = '{{csrf_token()}}'
    </script>

    @if (request()->cookie('ast'))
    <meta name="ast" content="{{ request()->cookie('ast') }}" />
    @else
    <script>
        setTimeout(() => {
            document.getElementById('logout-form').submit();
        }, 200);
    </script>
    @endif


<style>
    .axil-submenu
    {
    }
</style>

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        <header id="page-topbar">
            <div class="layout-width">
                <div class="navbar-header">
                    <div class="d-flex">
                        <div class="navbar-brand-box horizontal-logo">
                            <a   class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{asset('/assets/images/rc-favicon.png')}}" alt="" height="50">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{asset('/assets/images/rc-favicon.png')}}" alt="" height="17">
                                </span>
                            </a>

                            <a href="{{route('home')}}"   class="logo logo-light">
                                <span class="favicon">
                                    <img src="{{{asset('/assets/images/rc-favicon.png')}}}" alt="" height="50">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{asset('/assets/images/logo-light.png?v=5')}}" alt="" height="17">
                                </span>
                            </a>
                        </div>

                      
                        <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger" id="topnav-hamburger-icon">
                            <span class="hamburger-icon open">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                        </button>

                    </div>

                    <div class="d-flex align-items-center">

                        <div class="dropdown d-md-none topbar-head-dropdown header-item">
                            <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" id="page-header-search-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-search fs-22"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-search-dropdown">
                                <form class="p-3">
                                    <div class="form-group m-0">
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search ..." aria-label="Consignee's username">
                                            <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="dropdown ms-sm-3 header-item topbar-user">
                            <button type="button bg-white" class="btn" id="page-header-user-dropdown2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            </button>


                            

                            <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="d-flex align-items-center">
                                    <img class="rounded-circle header-profile-user" src="{{asset('https://ui-avatars.com/api/?name='.implode('+',explode(' ',Auth::user()->name)))}}&background=fcd106&color=fff" alt="Header Avatar">
                                    <span class="text-start ms-xl-2">
                                        <span class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{Auth::user()->name}}</span>
                                    </span>
                                </span>
                            </button>

                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <h6 class="dropdown-header">مرحبـاً {{Auth::user()->name}} !</h6>
                                <a class="dropdown-item" href="{{route('home')}}" ><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> لوحة مدير النظام</a>
                                <a class="dropdown-item" href="#" onclick="document.getElementById('logout-form').submit()"><i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span class="align-middle" data-key="t-logout">تسجيل الخــروج</span></a>

                                <form action="{{route('logout')}}" method="post" id="logout-form">
                                    @csrf

                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- ========== App Menu ========== -->
        <div class="app-menu navbar-menu">
            <!-- LOGO -->
            <div class="navbar-brand-box">

                   <!-- Dark Logo-->
                <a   class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{asset('/assets/images/rc-favicon.png')}}" alt="" height="50">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('/assets/images/logo-light.png?v=2')}}" alt="" height="35">
                    </span>
                </a>
                <!-- Light Logo-->
                <a href="{{route('home')}}"   class="logo logo-light">
                    <img src="{{asset('/assets/images/re-store-v.png?v=2')}}" alt="" height="90">
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>

            <div id="scrollbar">
                <div class="container-fluid">

                    <div id="two-column-menu">
                    </div>
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="nav-item p-2">
                            {{-- <form action="{{route('cards.find')}}">
                                <input type="text" name="card_number" id="" class="form-control" placeholder="ادخل رقم البطاقة للبحث عنها">
                            </form> --}}
                        </li>
                        <li class="nav-item p-2">
                            {{-- <form action="{{route('cards.find')}}">
                                <input type="text" name="code" id="" class="form-control" placeholder="ادخل كود البطاقة  للبحث عنها">
                            </form> --}}
                        </li>

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="{{route('home')}}">
                                <i class="ri-dashboard-line"></i> <span>الرئيسية</span>
                            </a>
                        </li>

                        @include('layouts.menu')

                        <li class="nav-item">
                            <a class="nav-link menu-link" href="#" onclick="document.getElementById('logout-form').submit()">
                                <i class="ri-logout-circle-line"></i> <span>تسجيـل الخـروج</span>
                            </a>
                        </li>

                    </ul>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">@yield('title') </h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">@yield('area')</a></li>
                                        <!-- <li class="breadcrumb-item active">Starter</li> -->
                                        @yield('breadcrumb')
                                    </ol>
                                </div>

                            </div>
                        </div>

                    </div>
                    <!-- end page title -->

                    <!-- content -->
                    <div class="row" id="app">
                        <div class="col-12 mt-1">
                            <div class="my-2"> @include('layouts.messages')</div>

                            @yield('content')
                        </div>
                    </div>
                    <!-- content -->

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-right">
                                <p class="mb-0 text-dark">&copy;
                                    <script>document.write(new Date().getFullYear())</script> تم التفيذ بواسطة <br>   <a  href="tel:+218 94-4-336674" class="text-primary"><span>AISHA ALTERY </span></a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->



    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->
    <!-- Theme Settings here -->


    <!-- Theme Settings -->


    <!-- JAVASCRIPT -->

    <script src="{{asset('/js/app.js?v=2')}}"></script>
    <script src="{{asset('/assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('/assets/libs/node-waves/waves.min.js')}}"></script>
    <script src="{{asset('/assets/libs/feather-icons/feather.min.js')}}"></script>
    <script src="{{asset('/assets/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
    <script src="{{asset('/assets/js/plugins.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{asset('/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    @yield('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js" integrity="sha512-6+YN/9o9BWrk6wSfGxQGpt3EUK6XeHi6yeHV+TYD2GR0Sj/cggRpXr1BrAQf0as6XslxomMUxXp2vIl+fv0QRA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.5/js/standalone/selectize.min.js" integrity="sha512-JFjt3Gb92wFay5Pu6b0UCH9JIOkOGEfjIi7yykNWUwj55DBBp79VIJ9EPUzNimZ6FvX41jlTHpWFUQjog8P/sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
   

    <script src="{{asset('/assets/js/app.js?v=2')}}"></script>
    <script src="{{asset('/assets/js/custom.js')}}"></script>

    @if (session()->get("modal") && $errors->any())
    <script type="text/javascript">
        let modalId = '{{session()->get("modal")}}';
        var myModal = new bootstrap.Modal(document.getElementById(modalId), {})
        myModal.show();
        errorSound.play();
    </script>
    @endif
    @if (session()->has('success'))
    <script type="text/javascript">
        successSound.play();
    </script>
    @endif

    <script>
            document.addEventListener('DOMContentLoaded', function () {
                CKEDITOR.replace('editor', {
                    language: 'ar'
                });
            });
    </script>

</body>


</html>