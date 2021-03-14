<!-- Start of footer with subscribe
        ============================================= -->
@php
    $footer_data = json_decode(config('footer_data'));
@endphp
{{--    @if($footer_data->newsletter_form->status == 1)--}}
{{--        <section class="subscribe-bg clearfix">--}}
{{--            <div class="container">--}}
{{--                <div class="row clearfix">--}}
{{--                    <div class="col-12"><h3>Subscribe Our <span>Newsletter</span></h3></div>--}}
{{--                </div>--}}
{{--                <div class="row mtb-30 clearfix">--}}
{{--                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">--}}
{{--                        --}}{{--                @if($footer_data->short_description->status == 1)--}}
{{--                        --}}{{--                    <p>{!! $footer_data->short_description->text !!} </p>--}}
{{--                        --}}{{--                @endif--}}
{{--                        <p>Keep up to date, Get latest updates, news and special offers in your inbox...</p>--}}
{{--                    </div>--}}

{{--                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">--}}
{{--                        <form action="{{route("subscribe")}}" method="post">--}}
{{--                            @csrf--}}
{{--                            <div class="input-group">--}}
{{--                                <input type="email" id="subs_email" name="subs_email" class="form-control bcolor" placeholder="@lang('labels.frontend.layouts.partials.email_address')" />--}}
{{--                                <span class="input-group-btn"><input type="submit" id="submit" name="submit" class="btn btn-primary bradius text-uppercase" value="@lang('labels.frontend.layouts.partials.subscribe')" /></span>--}}
{{--                            </div>--}}
{{--                            @if($errors->has('subs_email'))--}}
{{--                                <p class="text-danger text-left">{{$errors->first('subs_email')}}</p>--}}
{{--                            @endif--}}
{{--                        </form>--}}
{{--                    </div>--}}

{{--                </div>--}}
{{--            </div>--}}
{{--        </section>--}}
{{--    @endif--}}

    @if($footer_data->bottom_footer->status == 1)
    <footer>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <div class="ft-title">About Us</div>
                    <p class="ft-text">Online interactive courses in photography and visual storytelling taught by the professionals.</p>
                    <a class="btn btn-outline-primary float-left" href="#">Learn Now</a>
                </div>

                <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <div class="ft-title">Quick Link</div>
                    <ul class="ft-links clearfix">
                        <li><a href="{{url('/')}}">Home</a></li>
                        <li><a href="{{route('howitwork')}}">HOW IT WORKS</a></li>
                        <li><a href="{{route('courses.all')}}">COURSES</a></li>
                        <li><a href="{{route('teachers.index')}}">INSTRUCTORS</a></li>
                        <li><a href="{{route('courses.reviews.all')}}">REVIEW</a></li>
                        <li><a href="{{route('workshops.all')}}">WORKSHOPS</a></li>
                        <li><a href="#">BLOG</a></li>
                        <li><a href="#">STUDENT GALLERY</a></li>
                    </ul>
                </div>
{{--                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">--}}
{{--                    <div class="ft-title clearfix">Links</div>--}}
{{--                    <ul class="ft-links clearfix">--}}
{{--                        @if(count($custom_menus) > 0 )--}}
{{--                            @foreach($custom_menus as $menu)--}}
{{--                                @if($menu['id'] == $menu['parent'])--}}
{{--                                    @if(count($menu->subs) == 0)--}}
{{--                                        <li>--}}
{{--                                            <a class="nav-link {{ active_class(Active::checkRoute('frontend.user.dashboard')) }}"--}}
{{--                                               href="{{asset($menu->link)}}"--}}
{{--                                               id="menu-{{$menu->id}}">{{trans('custom-menu.'.$menu_name.'.'.str_slug($menu->label))}}</a>--}}
{{--                                        </li>--}}
{{--                                    @else--}}
{{--                                        @foreach($menu->subs as $item)--}}
{{--                                            <li><a class="dropdown-item" id="menu-{{$item->id}}" href="{{$item->link}}">{{trans('custom-menu.'.$menu_name.'.'.str_slug($item->label))}}</a></li>--}}
{{--                                        @endforeach--}}
{{--                                    @endif--}}

{{--                                @endif--}}
{{--                            @endforeach--}}
{{--                        @endif--}}
{{--                    </ul>--}}
{{--                </div>--}}
                <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <div class="ft-title">Contact Us</div>
                    <div class="ft-text"><span class="fa fa-envelope"></span> support@thecompellingimage.com</div>
                </div>
                @if(($footer_data->social_links->status == 1) && (count($footer_data->social_links->links) > 0))
                    <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <div class="ft-title">Social Network</div>
                        <p style="color: #ffffff">Follow Us</p>
                        <ul class="social-links">
                            @foreach($footer_data->social_links->links as $item)
                                <li><a href="{{$item->link}}" target="__blank"><i class="fa fa-{{$item->icon}}"></i></a></li>
                            @endforeach
                        </ul>
                        <br>
                        <div class="ft-text"><b>Post your own best shot at <br> <a href="https://www.instagram.com/explore/tags/thecompellingimage/" target="__blank">#thecompellingimage</a></b></div>
                    </div>
                @endif
            </div>
        </div>
    </footer>

{{--    @if($footer_data->copyright_text->status == 1)--}}
{{--    <div class="topbar clearfix">--}}
{{--        <div class="container">--}}
{{--            <div class="row clearfix">--}}
{{--                <div class="col-12">--}}
{{--                    <div class="label">{!!  $footer_data->copyright_text->text !!}</div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    @endif--}}

    <div class="bottombar">
        <div class="container">
            <div class="row clearfix">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="copyright1">
                        <a href="/privacy-policy">Privacy Policy</a> | <a href="/sitemap">Sitemap</a> | <a href="/enquiries/new">Contact</a> |
                        Copyright©2020.TheCompellingImage | Powered by : <a href="http://www.digiinteracts.com/" target="_blank">DigiInteracts.com</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endif
<!-- End of footer with subscribe
            ============================================= -->