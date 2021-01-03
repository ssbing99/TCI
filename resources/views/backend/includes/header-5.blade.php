<nav class="navbar fixed-top navbar-expand-md navbar-dark bg-primary mb-3">
    <div class="flex-row d-flex">
        <button type="button" class="navbar-toggler mr-2 " data-toggle="offcanvas" title="Toggle responsive left sidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="#" title="The Compelling Image"><img src="{{asset('assets_new/images/logo_black.png')}}" class="img-fluid" /></a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsingNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse" id="collapsingNavbar">
        <ul class="navbar-nav ml-auto">
            @if(count($custom_menus) > 0 )
                @foreach($custom_menus as $menu)
                    @if($menu['id'] == $menu['parent'])
                        @if($menu->hide_in_header != 'Y')
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
                                        @foreach($menu->subs as $item)
                                            @include('frontend.layouts.partials.dropdown3', $item)
                                        @endforeach
                                    </div>
                                </li>
                            @endif
                        @endif
                    @endif
                @endforeach
            @endif
            <li class="nav-item"><a class="nav-link" href="#search"><i class="fa fa-search"></i></a></li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('cart.index')}}"><div class="bag"><img src="{{asset('assets_new/images/icons/bag_green.png')}}" />
                        @if(auth()->check() && Cart::session(auth()->user()->id)->getTotalQuantity() != 0)
                            <span class="badge badge-danger position-absolute">{{Cart::session(auth()->user()->id)->getTotalQuantity()}}</span>
                        @endif
                    </div>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown"><i class="fa fa-user-circle-o" style="font-size: 24px;"></i> John Doe</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">@lang('navs.frontend.dashboard')</a>
                    <a class="dropdown-item" href="{{ route('frontend.auth.logout') }}">@lang('navs.general.logout')</a>
                </div>
            </li>
        </ul>
        <div id="search">
            <button type="button" class="close">×</button>
            <form action="{{route('search')}}" method="get">
                <input type="search" name="q" value="" placeholder="type keyword(s) here" />
                <button type="submit" class="btn btn-primary">@lang('labels.frontend.home.search')</button>
            </form>
        </div>
    </div>
</nav>