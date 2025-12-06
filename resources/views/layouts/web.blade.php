<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>re-store CO | @yield('title') </title>
    <meta name="robots" content="index, follow" />
    <meta name="description" content="re-store CO: الوجهة المثلى لجميع احتياجاتك من الشحن والأكواد. اكتشف خدماتنا المتميزة وتواصل معنا لمزيد من المعلومات.">
    <meta name="keywords" content="re-store CO, محطه شفتر, شافتر, re-store station, شحن, أكواد الألعاب">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/rc-favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200;300;400;500;700;800;900&display=swap" rel="stylesheet">

    <!-- CSS ============================================ -->
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/bootstrap.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/flaticon/flaticon.css') }}">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/jquery-ui.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/sal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor/base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link href="{{asset('/assets/css/custom.min.css?v=')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <link href="{{ asset('assets/css/dark-mode.css') }}" rel="stylesheet">

    <style>

    </style>
    @yield('styles')

    @include('layouts.styles')
    <meta content="ar" name="language" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        let csrfToken = '{{csrf_token()}}'
    </script>

    @if (request()->cookie('ast'))
    <meta name="ast" content="{{ request()->cookie('ast') }}" />
    @else
        @if (auth('customer')->check())
        
        @endif
    @endif

    @yield('styles')
    <style>
        body,
        a,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        p,
        button,
        label,
        table,
        ul,
        li {
            font-family: 'Tajawal', sans-serif !important;
        }

        .axil-product .product-content .title {
            margin-bottom: 5px !important;
        }

        .axil-product .product-content .product-price-variant {
            margin-top: 0 !important;
        }

        .axil-product .product-content .product-price-variant .price {
            margin-top: 5px !important;
        }

        .whatsapp-float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            right: 40px;
            background-color: #25d366;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            box-shadow: 2px 2px 3px #999;
            z-index: 1000;
        }

        .whatsapp-float i {
            margin-top: 16px;
        }

        .service-box {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 10px;
        }

        .service-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
            border-color: #25d366;
        }

        .service-box .icon img {
            width: 50px;
            height: auto;
            transition: transform 0.3s ease;
        }

        .service-box:hover .icon img {
            transform: scale(1.1);
        }

        .service-box .title {
            margin: 0;
            font-size: 1.2em;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .service-box:hover .title {
            color: #25d366;
        }

        .btn-facebook {
            background-color: #161616;
            color: white;
            font-size: 1.2em;
            padding: 10px 0;
        }

        .btn-google {
            background-color: #db4437;
            color: white;
            font-size: 1.2em;
            padding: 10px 0;
        }

        .btn {
            border-radius: 0.25rem;
            font-weight: bold;
        }

        .card {
            border: 1px solid #e3e6f0;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .card-header {
            background-color: #6606ce;
            color: #fff;
            font-size: 1.25rem;
            text-align: center;
        }

        .toast-message {
            font-size: 18px !important;
        }

        /* Disable styles */
        .disabled-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.5);
            z-index: 2000;
            display: flex;
            justify-content: center;
            align-items: center;
            display: none;
        }

        .disabled-overlay.active {
            display: flex;
        }

        .disabled-overlay .loader {
            border: 8px solid #f3f3f3;
            border-radius: 50%;
            border-top: 8px solid #3498db;
            width: 60px;
            height: 60px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>


<style>
/* Side Menu Styles */
.side-menu {
    height: 100%;
    width: 0;
    position: fixed;
    top: 0;
    left: 0;
    background-color: #fff;
    overflow-x: hidden;
    transition: 0.3s;
    z-index: 9999;
    padding-top: 60px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
}

.side-menu-header {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    /* background-color: #6606ce; */
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
}

.side-menu-header .logo img {
    width: 120px;
}

.side-menu-header .close-btn {
    font-size: 30px;
    background: none;
    border: none;
    color: #fff;
    cursor: pointer;
}

.side-menu-list {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.side-menu-list li {
    padding: 10px 20px;
}

.side-menu-list li a {
    text-decoration: none;
    font-size: 18px;
    color: #333;
    display: block;
}

.side-menu-list li a:hover {
    color: #6606ce;
}

/* Side Menu Styles */
.side-menu {
    height: 100%;
    width: 0;
    position: fixed;
    top: 0;
    left: 0; /* Adjust to 'right: 0;' if you want the menu from the right */
    background-color: #fff;
    overflow-x: hidden;
    transition: 0.3s;
    z-index: 9999;
    padding-top: 60px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
}

.side-menu-header {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    /* background-color: #6606ce; */
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
}

.side-menu-header .logo img {
    width: 120px;
}

.side-menu-header .close-btn {
    font-size: 30px;
    background: none;
    border: none;
    color: #fff;
    cursor: pointer;
}

.side-menu-list {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.side-menu-list li {
    padding: 10px 20px;
}

.side-menu-list li a {
    text-decoration: none;
    font-size: 18px;
    color: #333;
    display: block;
}

.side-menu-list li a:hover {
    color: #6606ce;
}


/* Overlay Styles */
.overlay {
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 9998;
    display: none; /* Initially hidden */
    opacity: 0;
    transition: opacity 0.3s ease;
}

.overlay.active {
    display: block;
    opacity: 1;
}


</style>

<style>
    .submenu {
        list-style: none;
        padding-left: 20px;
        display: none; /* Initially hidden */
    }
    
    .submenu.open {
        display: block; /* Show submenu when .open class is applied */
    }
    
    .submenu li a {
        font-size: 16px;
        color: #555;
        padding: 5px 0;
        display: block;
        text-decoration: none;
    }

    .submenu li a:hover {
        color: #6606ce;
    }
    
    /* Arrow icon rotation for opened submenu */
    .menu-item-has-children a {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .menu-item-has-children i {
        transition: transform 0.3s ease;
    }

    .submenu.open ~ a i {
        transform: rotate(180deg); /* Rotate arrow when submenu is open */
    }

    .form-group label
    {
        background: #151923!important;
    }
</style>

    @stack('styles')
</head>

<body class="sticky-header">

    @if ($content->message)
    <div class=" p-3" style="background:#161616!important;">
        <p class="font-weight-bold text-center" style="font-weight: bold !important;color:white;"> {{$content->message}} </p>
    </div>
    @endif

    <div id="overlay" class="overlay"></div>


    <header class="header axil-header header-style-5">
        <div id="axil-sticky-placeholder"></div>
        <div class="axil-mainmenu">
            <div class="container">
                <div class="header-navbar">
                    <div class="">
                        <a href="/" class="logo logo-dark">
                            <img src="{{ asset('assets/images/re-store-v.png?v=2') }}" width="160" alt="re-store Station Logo">
                        </a>
                        <a href="/" class="logo logo-light">
                            <img src="{{ asset('assets/images/re-store-v.png?v=2') }}" alt="re-store Station Logo">
                        </a>
                    </div>
                    <div class="header-main-nav">
                        <!-- Start Mainmanu Nav -->
                        <nav class="mainmenu-nav">
                            <button class="mobile-close-btn mobile-nav-toggler"><i class="fas fa-times"></i></button>
                            <div class="mobile-nav-brand">
                                <a href="/" class="logo">
                                    <img src="{{ asset('assets/images/re-store-v.png?v=2') }}" alt="re-store Station Logo">
                                </a>
                            </div>
                            <ul class="mainmenu">
                                <li><a href="/">الصفحة الرئيسية</a></li>
                                <li><a href="/about"> من نحن؟ </a></li>

                                <li class="menu-item-has-children">
                                    <a href="#">الأقسام</a>
                                    <ul class="axil-submenu" style="background:#151923!important;">
                                        <li><a href="{{ route('category', ['category_id' => 1]) }}">الشحن عبر الID</a></li>
                                        <li><a href="{{ route('category', ['category_id' => 2]) }}">أكواد الالعاب</a></li>
                                    </ul>
                                </li>

                                <li><a href="{{ route('contact') }}">تواصل معنا</a></li>
                            </ul>
                        </nav>
                        <!-- End Mainmanu Nav -->
                    </div>
                    <div class="header-action">
                        <ul class="action-list">
                            {{-- <li>
                                <div class="dropdown">
                                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ $paymentMethods->firstWhere('id', $currentPaymentMethod)->name ?? 'Select Payment Method' }}
                                        <img src="{{ asset('images/payment_methods/' . $paymentMethods->firstWhere('id', $currentPaymentMethod)->image) }}" width="20" />
                                    </button>
                                    <ul class="dropdown-menu">
                                        @foreach($paymentMethods as $paymentMethod)
                                        <li>
                                            <a class="dropdown-item {{ $paymentMethod->id == $currentPaymentMethod ? 'active' : '' }}" href="#" data-id="{{ $paymentMethod->id }}">
                                                {{ $paymentMethod->name }} <img src="{{ asset('images/payment_methods/' . $paymentMethod->image) }}" width="20" />
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li> --}}


                            <li>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-icon" id="openSearchModal">
                                        <i class="fa fa-search fs-22"></i>
                                    </button>
                                </div>
                                
                            </li>
                        </li>
                        <li class="shopping-cart">
                            <a href="{{route('website.cart', ['payment_method_id' => $currentPaymentMethod ])}}" class="cart-dropdown-btn">
                                <span class="cart-count">
                                    {{$count}}
                                </span>
                                <i class="flaticon-shopping-cart"></i>
                            </a>
                        </li>
                            <li class="my-account">
                                <a href="javascript:void(0)">
                                    <i class="flaticon-person"></i>
                                </a>
                                <div class="my-account-dropdown">
                                    @if (auth('customer')->user())
                                    <ul>
                                        <li>
                                            <a href="{{ route('profile') }}"> <i class="fa fa-user"></i> ملفي الشخصي </a>
                                        </li>
                                        <li>
                                            <a href="/profile#nav-orders"> <i class="fa fa-cart-shopping"></i> قائمة المشتريات </a>
                                        </li>
                                    </ul>
                                    @else
                                    <ul>
                                        <li>
                                            <a href="{{ route('register') }}">انشاء حساب جديد</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('website.do-login') }}">تسجيل دخول</a>
                                        </li>
                                    </ul>
                                    @endif
                                </div>
                            </li>
                            <li class="axil-mobile-toggle">
                                <button class="menu-btn mobile-nav-toggler">
                                    <i class="flaticon-menu-2"></i>
                                </button>
                            </li>                                                        
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- End Header -->


    <!-- Side Menu -->
<!-- Side Menu -->
<div id="sideMenu" class="side-menu">
    <div class="side-menu-header">
        <a href="/" class="logo">
            <img src="{{ asset('assets/images/re-store-v.png?v=2') }}" alt="re-store Station Logo">
        </a>
        <button class="close-btn" onclick="closeSideMenu()">&times;</button>
    </div>
    <ul class="side-menu-list">
        <li><a href="/">الصفحة الرئيسية</a></li>
        <li><a href="/about">من نحن؟</a></li>
        
        <!-- Submenu for Categories -->
        <li class="menu-item-has-children">
            <a href="#" onclick="toggleSubMenu(event)">الأقسام <i class="fa fa-chevron-down"></i></a>
            <ul class="submenu">
                @foreach ($categories as $category)
                    <li><a href="{{ route('website.category', $category ) }}"> {{$category->name}} </a></li>
                @endforeach
            </ul>
        </li>
        
        <li><a href="{{ route('contact') }}">تواصل معنا</a></li>
        <li><a href="{{ route('policy') }}"> سياسة الخصوصية </a></li>
        <li><a href="{{ route('returns') }}"> سياسة الاسترجاع </a></li>
        <li><a href="{{ route('faq') }}">الأسئلة الشائعة</a></li>
    </ul>
</div>



    <main class="main-wrapper mt-4">
        <div class="container">
            <div class="my-2"> @include('layouts.messages')</div>

            <div class="">
                <form action="{{route('logout')}}" method="post" id="logout-form">
                    @csrf

                </form>
            </div>
        </div>

        @yield('content')
    </main>

    <!-- Start Footer Area  -->
    <footer class="axil-footer-area footer-style-1 bg-color-white">
        <!-- Start Footer Top Area  -->
        <div class="footer-top separator-top">
            <div class="container">
                <div class="row">
                    <!-- Start Single Widget  -->
                    <div class="col-lg-3 col-6">
                        <div class="axil-footer-widget pr--30">
                            <div class="logo">
                                <a href="/">
                                    <img class="" src="{{ asset('assets/images/re-store-v.png?v=2') }}" alt="re-store Station Logo">
                                </a>
                            </div>
                            <div class="inner">
                                <p style="font-weight: bold !important;" class="text-dark"> تابعنا على مواقع التواصل الاجتماعي </p>
                                <div class="social-share">
                                    @if ($content->facebook)
                                    <a href="{{ $content->facebook }}"><i class="fab fa-facebook-f"></i></a>
                                    @endif
                                    @if ($content->instagram)
                                    <a href="{{ $content->instagram }}"><i class="fab fa-instagram"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Widget  -->
                    <!-- Start Single Widget  -->
                    <div class="col-lg-3 col-6">
                        <div class="axil-footer-widget">
                            <h5 class="widget-title" style="font-weight: bold !important;">روابط مهمة</h5>
                            <div class="inner">
                                <ul>
                                    <li><a href="{{ route('about') }}">حول re-store</a></li>
                                    <li><a href="{{ route('category', ['category_id' => 1]) }}">الشحن عن طريق ال ID</a></li>
                                    <li><a href="{{ route('category', ['category_id' => 2]) }}"> أكواد الالعاب </a></li>
                                    <li><a href="{{ route('contact') }}">تواصل معنا</a></li>
                                    <li><a href="{{ route('policy') }}"> سياسة الخصوصية </a></li>
                                    <li><a href="{{ route('returns') }}"> سياسة الاسترجاع </a></li>
                                    <li><a href="{{ route('faq') }}">  الاسئلة الشائعة  </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Widget  -->
                </div>
            </div>
        </div>

                    <!-- Search Modal -->
            <!-- Search Modal -->
            <div class="modal modal-xl p-4 fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <form id="searchForm" class="p-3">
                                <div class="row">
                                    <div class="col-10">
                                        <div class="input-group">
                                            <input type="text" id="searchQuery" class="form-control bg-light border border-secondary" style="height: 40px !important;" placeholder="أدخل كلمة البحث...">
                                        </div>
                                    </div>
                                    <div class="col-2 d-flex align-items-center">
                                        <button type="submit" class="btn btn-primary text-light">بحث</button>
                                    </div>
                                </div>
                            </form>
                            <div id="searchResults" class="row mt-4">
                                <!-- Results will be appended here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        <!-- End Footer Top Area  -->
        <!-- Start Copyright Area  -->
        <div class="copyright-area copyright-default separator-top">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-7 col-lg-12">
                        <div class="copyright-left d-flex flex-wrap justify-content-xl-start justify-content-center">
                            <ul class="quick-link">
                                <li><a href="{{ route('policy') }}">سياسة الخصوصية</a></li>
                                <li><a href="{{ route('returns') }}"> سياسة الاسترجاع </a></li>
                                <li><a href="{{ route('faq') }}">  الاسئلة الشائعة  </a></li>
                            </ul>
                            <ul class="quick-link">
                                <li>© جميع الحقوق محفوظة لصالح re-store </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-12">
                        <div class="copyright-right d-flex flex-wrap justify-content-xl-end justify-content-center align-items-center">
                            <ul class="payment-icons-bottom quick-link">
                                @foreach ($paymentMethods as $paymentMethod)
                                <li><img src="{{ asset('images/payment_methods/' . $paymentMethod->image) }}" width="30" alt="Payment Method"></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Copyright Area  -->
    </footer>
    <!-- End Footer Area  -->

    @php
    $content = App\Models\Content::first();
    @endphp

    @if ($content->whatsapp)
    <a href="https://wa.me/{{ $content->whatsapp }}" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>
    @endif

    <!-- JS ============================================ -->
    <script src="{{ asset('assets/js/vendor/modernizr.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/jquery.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/js.cookie.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/sal.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/counterup.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/waypoints.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js" integrity="sha512-gY25nC63ddE0LcLPhxUJGFxa2GoIyA5FLym4UJqHDEMHjp8RET6Zn/SHo1sltt3WuVtqfyxECP38/daUc/WVEA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    @yield('scripts')

    <script>
        $(document).ready(function() {
            $('.slider-activation-two').slick({
                dots: true,
                infinite: true,
                speed: 700, // 0.7 ثانية
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 2000,
                arrows: true,
                prevArrow: '<button type="button" class="slick-prev">Previous</button>',
                nextArrow: '<button type="button" class="slick-next">Next</button>',
            });
        });
    </script>

    <!-- Main JS -->
    <script>
        $(document).ready(function() {
            // Update payment method via AJAX
            $('.dropdown-item').on('click', function(e) {
                e.preventDefault();
                var paymentMethodId = $(this).data('id');

                $.ajax({
                    url: '/update-payment-method',
                    type: 'POST',
                    data: {
                        payment_method_id: paymentMethodId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            location.reload(); // Reload the page to reflect the changes
                        }
                    }
                });
            });
        });
    </script>

    <script>
            document.getElementById('openSearchModal').addEventListener('click', function () {
                var searchModal = new bootstrap.Modal(document.getElementById('searchModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                searchModal.show();
            });

    </script>

    <script>
            $(document).ready(function() {
    // Handle search form submission
    $('#searchForm').on('submit', function(e) {
        e.preventDefault(); // Prevent form from submitting the traditional way

        var query = $('#searchQuery').val(); // Get the search query
        
        // Clear previous results
        $('#searchResults').html('');

        // Make AJAX request to the server to fetch products
        $.ajax({
            url: '/search-products', // Adjust the URL to your search route
            type: 'GET',
            data: {
                query: query, // Send the search query as a parameter
            },
            success: function(response) {
                // Check if products were found
                if (response.products.length > 0) {
                    response.products.forEach(function(product) {
                        // Append each product as a card
                        $('#searchResults').append(`
                            <div class="col-6 mb-3">
                                <div class="card">
                                    <img src="/storage/${product.image}" class="card-img-top" alt="${product.name}">
                                    <div class="card-body">
                                        <h5 class="card-title">${product.name}</h5>
                                        <div class="small text-muted">${product.description}</div>
                                        <a href="/product-details/${product.id}" class="btn btn-primary">عرض المنتج</a>
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                } else {
                    $('#searchResults').append('<p class="text-center">لم يتم العثور على نتائج.</p>');
                }
            },
            error: function() {
                $('#searchResults').append('<p class="text-center text-danger">لم يتم العثور على اي نتائج.</p>');
            }
        });
    });
});

    </script>
<script>
    $(function() {
        // Function to open the side menu and show overlay
        function openSideMenu() {
            $('#sideMenu').css('width', '250px'); // Adjust the width as needed
            $('#overlay').addClass('active'); // Show the overlay
        }

        // Function to close the side menu and hide overlay
        function closeSideMenu() {
            $('#sideMenu').css('width', '0');
            $('#overlay').removeClass('active'); // Hide the overlay
        }

        // Toggle side menu on button click
        $('.mobile-nav-toggler').click(function() {
            var sideMenuWidth = $('#sideMenu').width();
            if (sideMenuWidth === 0) {
                openSideMenu();
            } else {
                closeSideMenu();
            }
        });

        // Close side menu when the close button is clicked
        $('.side-menu .close-btn, #overlay').click(function() {
            closeSideMenu();
        });
    });
</script>


<script>
    function toggleSubMenu(event) {
        event.preventDefault();
        var submenu = event.target.nextElementSibling;
        submenu.classList.toggle('open');
    }
</script>


    <script src="{{ asset('assets/js/rtl-main.js') }}"></script>
</body>

</html>
