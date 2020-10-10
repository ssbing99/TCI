<!-- Start of footer with subscribe
        ============================================= -->

    <section class="subscribe-bg clearfix">
        <div class="container">
            <div class="row clearfix">
                <div class="col-12"><h3>Subscribe Our <span>Newsletter</span></h3></div>
            </div>
            <div class="row mtb-30 clearfix">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
    {{--                @if($footer_data->short_description->status == 1)--}}
    {{--                    <p>{!! $footer_data->short_description->text !!} </p>--}}
    {{--                @endif--}}
                    <p>Keep up to date, Get latest updates, news and special offers in your inbox...</p>
                </div>
                @if($footer_data->newsletter_form->status == 1)
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <form method="post" action="{{route("subscribe")}}" role="form">
                        @csrf
                        <div class="input-group">
                            <input type="email" id="subscribeemail" name="subscribeemail" class="form-control bcolor" placeholder="@lang('labels.frontend.layouts.partials.email_address')" />
                            <span class="input-group-btn"><input type="submit" id="submit" name="submit" class="btn btn-primary bradius text-uppercase" value="@lang('labels.frontend.layouts.partials.subscribe')" /></span>
                        </div>
                        @if($errors->has('subs_email'))
                            <p class="text-danger text-left">{{$errors->first('subs_email')}}</p>
                        @endif
                    </form>
                </div>
                @endif
            </div>
        </div>
    </section>

    @if($footer_data->bottom_footer->status == 1)
    <footer>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <img src="{{asset("assets_new/images/footer-logo.png")}}" alt="Image goes here" />
                </div>
                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <div class="ft-title clearfix">Links</div>
                    <ul class="ft-links clearfix">
                        <li><a href="#">Courses & Events</a></li>
                        <li><a href="#">Articles & Videos</a></li>
                        <li><a href="#">Long Term Programs</a></li>
                        <li><a href="#">Purchase</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                @if(($footer_data->social_links->status == 1) && (count($footer_data->social_links->links) > 0))
                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <div class="ft-title clearfix">Follow Us</div>
                    <ul class="slinks clearfix">
{{--                        @foreach($footer_data->social_links->links as $item)--}}
{{--                            <li><a href="{{$item->link}}"><i class="{{$item->icon}}"></i></a></li>--}}
{{--                        @endforeach--}}
                        <li><a href="#"><img src="{{asset("assets_new/images/twitter.png")}}" alt="Image goes here" /></a></li>
                        <li><a href="#"><img src="{{asset("assets_new/images/facebook.png")}}" alt="Image goes here" /></a></li>
                        <li><a href="#"><img src="{{asset("assets_new/images/gmail.png")}}" alt="Image goes here" /></a></li>
                        <li><a href="#"><img src="{{asset("assets_new/images/instagram.png")}}" alt="Image goes here" /></a></li>
                        <li><a href="#"><img src="{{asset("assets_new/images/pinterest.png")}}" alt="Image goes here" /></a></li>
                        <li><a href="#"><img src="{{asset("assets_new/images/youtube.png")}}" alt="Image goes here" /></a></li>
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </footer>

    @if($footer_data->copyright_text->status == 1)
    <div class="topbar clearfix">
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <div class="label">{!!  $footer_data->copyright_text->text !!}</div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @endif
<!-- End of footer with subscribe
            ============================================= -->