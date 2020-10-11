@extends('frontend.layouts.app'.config('theme_layout'))

@section('title', 'Contact | '.app_name())
@section('meta_description', '')
@section('meta_keywords','')

@push('after-styles')
    <style>
        .my-alert{
            position: absolute;
            z-index: 10;
            left: 0;
            right: 0;
            top: 25%;
            width: 50%;
            margin: auto;
            display: inline-block;
        }
    </style>
@endpush

@section('content')
    @php
        $footer_data = json_decode(config('footer_data'));
    @endphp
    @if(session()->has('alert'))
        <div class="alert alert-light alert-dismissible fade my-alert show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>{{session('alert')}}</strong>
        </div>
    @endif

    <!-- Start of breadcrumb section
        ============================================= -->
    @if(config('theme_layout') == 5)
        <div class="banner custom-banner-bg">
            <div class="container">
                <div class="page-heading">
                    @lang('labels.frontend.contact.title')
                </div>
            </div>
        </div>
    @else
    <section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style">
        <div class="blakish-overlay"></div>
        <div class="container">
            <div class="page-breadcrumb-content text-center">
                <div class="page-breadcrumb-title">
                    <h2 class="breadcrumb-head black bold">{{env('APP_NAME')}} <span> @lang('labels.frontend.contact.title')</span></h2>
                </div>
            </div>
        </div>
    </section>
    @endif
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of contact section
        ============================================= -->
    @if(config('theme_layout') == 5)
        <section class="get-in-touch clearfix">
            <div class="container">
                <div class="row clearfix">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <h5>@lang('labels.frontend.layouts.partials.get_in_touch_text')</h5>
                        <div class="clearfix"></div>
                        <form class="mtb-30" action="{{route('contact.send')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control @if($errors->has('name')) border-bottom border-danger @endif" name="name" id="name" placeholder="@lang('labels.frontend.contact.name')" />
                                @if($errors->has('name'))
                                    <span class="help-block text-danger">{{$errors->first('name')}}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control @if($errors->has('email')) border-bottom border-danger @endif" name="email" id="email" placeholder="@lang('labels.frontend.contact.email')" />
                                @if($errors->has('email'))
                                    <span class="help-block text-danger">{{$errors->first('email')}}</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="number" class="form-control number @if($errors->has('phone')) border-bottom border-danger @endif" name="phone" id="phone" placeholder="@lang('labels.frontend.contact.phone_number')" />
                                @if($errors->has('phone'))
                                    <span class="help-block text-danger">{{$errors->first('phone')}}</span>
                                @endif
                            </div>
{{--                            <div class="form-group">--}}
{{--                                <input type="text" class="form-control @if($errors->has('address')) border-bottom border-danger @endif" name="address" id="address" placeholder="@lang('labels.frontend.contact.address')" />--}}
{{--                                @if($errors->has('address'))--}}
{{--                                    <span class="help-block text-danger">{{$errors->first('address')}}</span>--}}
{{--                                @endif--}}
{{--                            </div>--}}
                            <div class="form-group">
                                <textarea class="form-control @if($errors->has('message')) border-bottom border-danger @endif" name="message" id="message" placeholder="@lang('labels.frontend.contact.message')"></textarea>
                                @if($errors->has('message'))
                                    <span class="help-block text-danger">{{$errors->first('message')}}</span>
                                @endif
                            </div>
                            <button type="submit" name="submit" id="submit" class="btn btn-primary btn-lg" value="@lang('labels.frontend.contact.send')">@lang('labels.frontend.contact.send')</button>
                        </form>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3344.2766220456865!2d-96.70377268547395!3d33.049183477366554!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x864c19b3dc1c0001%3A0xdd9b7cba447aa925!2sSchool%20of%20Permaculture!5e0!3m2!1sen!2sin!4v1601741385740!5m2!1sen!2sin" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                    </div>
                </div>
            </div>
        </section>

    @else
        
    <section id="contact-page" class="contact-page-section">
        <div class="container">
            <div class="section-title mb45 headline text-center">
                <h2>@lang('labels.frontend.contact.keep_in_touch')</h2>
            </div>
            @if(($footer_data->social_links->status == 1) && (count($footer_data->social_links->links) > 0))
                <div class="social-contact text-center d-inline-block w-100">
                    @foreach($footer_data->social_links->links as $item)
                    <div class="category-icon-title text-center">
                        <a href="{{$item->link}}" target="_blank">
                            <div class="category-icon">
                                <i class="text-gradiant {{$item->icon}}"></i>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
    <!-- End of contact section
        ============================================= -->

    <!-- Start of contact area form
        ============================================= -->
    <section id="contact-form" class="contact-form-area_3 contact-page-version">
        <div class="container">
            <div class="section-title mb45 headline text-center">
                <h2>@lang('labels.frontend.contact.send_us_a_message')</h2>
            </div>

            <div class="contact_third_form">
                <form class="contact_form" action="{{route('contact.send')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="contact-info">
                                <input class="name" name="name" type="text" placeholder="@lang('labels.frontend.contact.your_name')">
                                @if($errors->has('name'))
                                    <span class="help-block text-danger">{{$errors->first('name')}}</span>
                                @endif
                            </div>

                        </div>
                        <div class="col-md-4">
                            <div class="contact-info">
                                <input class="email" name="email" type="email" placeholder="@lang('labels.frontend.contact.your_email')">
                                @if($errors->has('email'))
                                    <span class="help-block text-danger">{{$errors->first('email')}}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="contact-info">
                                <input class="number" name="phone" type="number" placeholder="@lang('labels.frontend.contact.phone_number')">
                                @if($errors->has('phone'))
                                    <span class="help-block text-danger">{{$errors->first('phone')}}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <textarea name="message" placeholder="@lang('labels.frontend.contact.message')"></textarea>

                @if($errors->has('message'))
                        <span class="help-block text-danger">{{$errors->first('message')}}</span>
                    @endif

                    @if(config('access.captcha.registration'))
                        <div class="contact-info mt-5 text-center">
                            {{ no_captcha()->display() }}
                            {{ html()->hidden('captcha_status', 'true')->id('captcha_status') }}
                            @if($errors->has('g-recaptcha-response'))
                                <p class="help-block text-danger mx0auto">{{$errors->first('g-recaptcha-response')}}</p>
                            @endif
                        </div><!--col-->
                    @endif


                    <div class="nws-button text-center  gradient-bg text-uppercase">
                        <button class="text-uppercase" type="submit" value="Submit">@lang('labels.frontend.contact.send_email') <i class="fas fa-caret-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- End of contact area form
        ============================================= -->


    <!-- Start of contact area
        ============================================= -->
    @include('frontend.layouts.partials.contact_area')
    <!-- End of contact area
        ============================================= -->
    @endif

@endsection
@push('after-scripts')
    @if(config('access.captcha.registration'))
        {{ no_captcha()->script() }}
    @endif
@endpush
