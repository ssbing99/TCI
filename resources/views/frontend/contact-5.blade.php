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
    <div class="banner custom-banner-bg">
        <div class="container">
            <div class="page-heading">
                @lang('labels.frontend.contact.title')
            </div>
        </div>
    </div>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of contact section
        ============================================= -->
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="contact-heading clearfix">@lang('labels.frontend.contact.keep_in_touch')</div>
                    @if(($footer_data->social_links->status == 1) && (count($footer_data->social_links->links) > 0))
                        <ul class="contact-slinks clearfix">
                            @foreach($footer_data->social_links->links as $item)
                                <li><a href="{{$item->link}}"><i class="fa {{$item->icon}}"></i></a></li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="bg-f9f9f9 clearfix">
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <div class="contact-heading clearfix">@lang('labels.frontend.contact.send_us_a_message')</div>
                </div>
                <div class="col-12">
                    <div class="bg-white clearfix">
                        <form class="contact_form" action="{{route('contact.send')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row clearfix">
                                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 form-group">
                                    <input type="text" class="form-control contactInput" name="name" id="name" placeholder="@lang('labels.frontend.contact.name')" required="" />
                                    @if($errors->has('name'))
                                        <span class="help-block text-danger">{{$errors->first('name')}}</span>
                                    @endif
                                </div>
                                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 form-group">
                                    <input type="email" class="form-control contactInput" name="email" id="email" placeholder="@lang('labels.frontend.contact.email')" required="" />
                                    @if($errors->has('email'))
                                        <span class="help-block text-danger">{{$errors->first('email')}}</span>
                                    @endif
                                </div>
                                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 form-group">
                                    <input type="tel" class="form-control contactInput" name="phone" id="phone" placeholder="@lang('labels.frontend.contact.phone_number')" required="" />
                                    @if($errors->has('phone'))
                                        <span class="help-block text-danger">{{$errors->first('phone')}}</span>
                                    @endif
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                                    <textarea class="form-control contactInput" id="message" name="message" placeholder="@lang('labels.frontend.contact.message')" rows="3" style="padding-top: 0;"></textarea>
                                    @if($errors->has('message'))
                                        <span class="help-block text-danger">{{$errors->first('message')}}</span>
                                    @endif
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                                    <center><input type="submit" id="submit" name="submit" class="btn btn-primary btn-lg" value="@lang('labels.frontend.contact.send')" /></center>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End of contact area form
        ============================================= -->

    <!-- Start of contact area
        ============================================= -->
    @include('frontend.layouts.partials.contact_area-5')
    <!-- End of contact area
        ============================================= -->

@endsection
@push('after-scripts')
    @if(config('access.captcha.registration'))
        {{ no_captcha()->script() }}
    @endif
@endpush
