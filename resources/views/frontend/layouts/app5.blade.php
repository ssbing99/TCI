<!DOCTYPE html>
@langrtl
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @endlangrtl

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
{{--        <title>@yield('title', app_name())</title>--}}
        <title>School of Permaculture</title>
        <meta name="description" content="@yield('meta_description', '')">
        <meta name="keywords" content="@yield('meta_keywords', '')">


    {{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}
    @stack('before-styles')

    <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        <!-- Otherwise apply the normal LTR layouts -->

        <link rel="stylesheet" href="{{asset('assets_new/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets_new/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets_new/css/owl.carousel.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets_new/css/owl.theme.default.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets_new/css/style.css')}}">
{{--        @if(config('favicon_image') != "")--}}
{{--            <link rel="shortcut icon" type="image/x-icon" href="{{asset('storage/logos/'.config('favicon_image'))}}"/>--}}
{{--        @else--}}
            <link rel="icon" type="image/x-icon" size="16x16" href="{{asset('assets_new/images/cropped-cropped-cropped-sop-fb-logo-150x150.jpg')}}" />
{{--        @endif--}}

        <link rel="icon" type="image/x-icon" href="{{asset('assets_new/images/cropped-cropped-cropped-sop-fb-logo-150x150.jpg')}}" sizes="32x32" />
        <link rel="icon" type="image/x-icon" href="{{asset('assets_new/images/cropped-cropped-cropped-sop-fb-logo-300x300.jpg')}}" sizes="192x192" />
        <link rel="apple-touch-icon-precomposed" href="{{asset('assets_new/images/cropped-cropped-cropped-sop-fb-logo-180x180.jpg')}}" sizes="32x32" />

        @stack('after-styles')
        @yield('css')
        @if(config('onesignal_status') == 1)
            {!! config('onesignal_data') !!}
        @endif
    @if(config('google_analytics_id') != "")

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{config('google_analytics_id')}}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config','{{config('google_analytics_id')}}');
        </script>
     @endif

        @if(!empty(config('custom_css')))
            <style>
                {!! config('custom_css')  !!}
            </style>
        @endif
    </head>
    <body class="{{config('layout_type')}}">
        <div class="topbar clearfix">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-sm-6 col-md-6 col-lg-6 col-xl-6 desktop">
                        <ul class="clearfix">
                            <li><i class="fa fa-envelope"></i> learn@schoolofpermaculture.com</li>
                            <li><i class="fa fa-phone"></i> (1) 214 856 8477</li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <ul class="float-right clearfix">
                            @if(auth()->check())
                                @can('view backend')
                                    <li><a href="{{ route('admin.dashboard') }}">@lang('navs.frontend.dashboard')</a></li>
                                @endcan
                                <li><a href="{{ route('frontend.auth.logout') }}">@lang('navs.general.logout')</a></li>
                            @else
                                @if(!auth()->check())
                                    <li><a id="openRegisterModal" href="#" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-user-plus"></i> @lang('navs.general.register')</a></li>
                                    <li><a id="openLoginModal" href="#" data-toggle="modal" data-target=".bd-example-modal-lg"><i class="fa fa-sign-in"></i> @lang('navs.general.login')</a></li>
                                @endif
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <nav class="navbar navbar-expand-md navbar-dark fixed-top" id="navbar">
            <div class="container">
                <!-- Brand -->
{{--                <a class="navbar-brand" href="/"><img src="images/logo.png" class="img-fluid" alt="Logo" /></a>--}}
                <a class="navbar-brand text-uppercase" href="{{url('/')}}">
                    <img class="img-fluid" src={{asset("assets_new/images/logo.png")}} alt="Logo">
                </a>
                <!-- Toggler/collapsibe Button -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Navbar links -->
                <div class="collapse navbar-collapse" id="collapsibleNavbar">
                    <ul class="navbar-nav ml-auto">
                        @if(count($custom_menus) > 0 )
                            @foreach($custom_menus as $menu)
                                @if($menu['id'] == $menu['parent'])
                                    @if(count($menu->subs) == 0)
                                        <li class="nav-item">
                                            <a class="nav-link {{ active_class(Active::checkRoute('frontend.user.dashboard')) }}"
                                               href="{{asset($menu->link)}}"
                                               id="menu-{{$menu->id}}">{{trans('custom-menu.'.$menu_name.'.'.str_slug($menu->label))}}</a>
                                        </li>
                                    @else
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">{{trans('custom-menu.'.$menu_name.'.'.str_slug($menu->label))}}</a>
                                            <div class="dropdown-menu">
{{--                                                <a class="dropdown-item" href="#">Link 1</a>--}}
{{--                                                <a class="dropdown-item" href="#">Link 2</a>--}}
{{--                                                <a class="dropdown-item" href="#">Link 3</a>--}}
                                                @foreach($menu->subs as $item)
                                                    @include('frontend.layouts.partials.dropdown3', $item)
                                                @endforeach
                                            </div>
                                        </li>
{{--                                        <li class="menu-item-has-children ul-li-block">--}}
{{--                                            <a href="#!">{{trans('custom-menu.'.$menu_name.'.'.str_slug($menu->label))}}</a>--}}
{{--                                            <ul class="sub-menu">--}}
{{--                                                @foreach($menu->subs as $item)--}}
{{--                                                    @include('frontend.layouts.partials.dropdown', $item)--}}
{{--                                                @endforeach--}}
{{--                                            </ul>--}}
{{--                                        </li>--}}
                                    @endif

                                @endif
                            @endforeach
                        @endif

                        <li class="nav-item"><a class="nav-link" href="#search"><i class="fa fa-search"></i></a></li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('cart.index')}}"><div class="bag"><img src="{{asset('assets_new/images/icons/bag.png')}}" />
                                @if(auth()->check() && Cart::session(auth()->user()->id)->getTotalQuantity() != 0)
                                    <span class="badge badge-danger position-absolute">{{Cart::session(auth()->user()->id)->getTotalQuantity()}}</span>
                                @endif
                                </div>
                            </a>
{{--                            <a class="nav-link" href="#"><div class="bag"><img src="{{asset('assets_new/images/icons/bag.png')}}" /></div></a></li>--}}
                        </li>
                    </ul>
                    <div id="search">
                        <button type="button" class="close">×</button>
                        <form action="{{route('search')}}" method="get">
                            <input type="search" value="" placeholder="type keyword(s) here" />
                            <button type="submit" class="btn btn-primary">@lang('labels.frontend.home.search')</button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

    <div id="app">
    {{--<div id="preloader"></div>--}}
    @include('frontend.layouts.modals.loginModal2')


    <!-- Start of Header section
    ============================================= -->
        <!-- Start of Header section
            ============================================= -->


        @yield('content')
        @include('cookieConsent::index')
        @if(!isset($no_footer))
            @include('frontend.layouts.partials.footer2')
        @endif

    </div><!-- #app -->

    <!-- Scripts -->
    @stack('before-scripts')
    <!-- For Js Library -->
        <script src="https://code.jquery.com/jquery.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="{{asset('assets_new/js/bootstrap.min.js')}}"></script>
        <script src="{{asset('assets_new/js/owl.carousel.min.js')}}"></script>
        {{--    <script src="{{asset('assets/js/jquery-2.1.4.min.js')}}"></script>--}}
{{--    <script src="{{asset('assets/js/popper.min.js')}}"></script>--}}
{{--    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>--}}
{{--    <script src="{{asset('assets/js/owl.carousel.min.js')}}"></script>--}}
{{--    <script src="{{asset('assets/js/jarallax.js')}}"></script>--}}
{{--    <script src="{{asset('assets/js/jquery.magnific-popup.min.js')}}"></script>--}}
{{--    <script src="{{asset('assets/js/lightbox.js')}}"></script>--}}
{{--    <script src="{{asset('assets/js/jquery.meanmenu.js')}}"></script>--}}
{{--    <script src="{{asset('assets/js/scrollreveal.min.js')}}"></script>--}}
{{--    <script src="{{asset('assets/js/jquery.counterup.min.js')}}"></script>--}}
{{--    <script src="{{asset('assets/js/waypoints.min.js')}}"></script>--}}
{{--    <script src="{{asset('assets/js/jquery-ui.js')}}"></script>--}}
{{--    <script src="{{asset('assets/js/gmap3.min.js')}}"></script>--}}
{{--    <script src="{{asset('assets/js/switch.js')}}"></script>--}}
{{--    <script src="{{asset('assets/js/script.js')}}"></script>--}}

        <script type="text/javascript">
            $(document).on("scroll", function(){
                if
                ($(document).scrollTop() > 86){
                    $("#navbar").addClass("shrink");
                }
                else
                {
                    $("#navbar").removeClass("shrink");
                }
            });
            //Searchbar js Starts
            $(function () {
                $('a[href="#search"]').on('click', function(event) {
                    event.preventDefault();
                    $('#search').addClass('open');
                    $('#search > form > input[type="search"]').focus();
                });

                $('#search, #search button.close').on('click keyup', function(event) {
                    if (event.target == this || event.target.className == 'close' || event.keyCode == 27) {
                        $(this).removeClass('open');
                    }
                });

            });
            //OwlCarousel Js Starts
            $(document).ready(function() {
                var owl = $('.owl-carousel');
                owl.owlCarousel({
                    margin: 30,
                    nav: true,
                    loop: true,
                    dots: false,
                    autoplay: true,
                    responsive: {
                        0: {
                            items: 1
                        },
                        600: {
                            items: 3
                        },
                        1000: {
                            items: 3
                        }
                    }
                })
            });
            $('#studenttestimonial').carousel({
                interval: 5000
            });
        </script>

    <script>
{{--        @if(request()->has('user')  && (request('user') == 'admin'))--}}

{{--        $('#myModal').modal('show');--}}
{{--        $('#loginForm').find('#email').val('admin@lms.com')--}}
{{--        $('#loginForm').find('#password').val('secret')--}}

{{--        @elseif(request()->has('user')  && (request('user') == 'student'))--}}

{{--        $('#myModal').modal('show');--}}
{{--        $('#loginForm').find('#email').val('student@lms.com')--}}
{{--        $('#loginForm').find('#password').val('secret')--}}

{{--        @elseif(request()->has('user')  && (request('user') == 'teacher'))--}}

{{--        $('#myModal').modal('show');--}}
{{--        $('#loginForm').find('#email').val('teacher@lms.com')--}}
{{--        $('#loginForm').find('#password').val('secret')--}}

{{--        @endif--}}
    </script>
    <script>
{{--        @if((session()->has('show_login')) && (session('show_login') == true))--}}
{{--        $('#myModal').modal('show');--}}
{{--                @endif--}}
{{--        var font_color = "{{config('font_color')}}"--}}
{{--        setActiveStyleSheet(font_color);--}}
    </script>
    @yield('js')

    @stack('after-scripts')

    @include('includes.partials.ga')
    @if(!empty(config('custom_js')))
        <script>
            {!! config('custom_js') !!}
        </script>
    @endif

    </body>
    </html>
