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
    <!-- Start of breadcrumb section
        ============================================= -->
    <header>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <h1>@lang('labels.frontend.contact.title')</h1>
                </div>
            </div>
        </div>
    </header>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of contact section
        ============================================= -->
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    @if(session()->has('alert'))
                        <div class="alert alert-success">
                            {{ session('alert') }}
                        </div>
                    @endif
                    <div class="contact-title clearfix">
                        Contact Us
                        <span>For general and support queries please use the contact form, or send an email to <a href="mailto:support@thecompellingimage.com" target="_blank">support@thecompellingimage.com</a>, and one of our team will be happy to help.</span>
                    </div>
                </div>
                <div class="col-12">
                    <form class="contact_form" action="{{route('contact.send')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row clearfix">
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                                <div class="form-group">
                                    <input type="text" name="firstName" id="firstName" class="form-control custom-form" placeholder="@lang('labels.frontend.contact.firstname')" required="" />
                                    @if($errors->has('firstName'))
                                        <span class="help-block text-danger">{{$errors->first('firstName')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                                <div class="form-group">
                                    <input type="text" name="lastName" id="lastName" class="form-control custom-form" placeholder="@lang('labels.frontend.contact.lastname')" required=""/>
                                    @if($errors->has('lastName'))
                                        <span class="help-block text-danger">{{$errors->first('lastName')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                                <div class="form-group">
                                    <input type="email" name="email" id="email" class="form-control custom-form" placeholder="@lang('labels.frontend.contact.email')" required=""/>
                                    @if($errors->has('email'))
                                        <span class="help-block text-danger">{{$errors->first('email')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 col-xxl-6">
                                <div class="form-group">
                                    <input type="text" name="phone" id="phone" class="form-control custom-form" placeholder="@lang('labels.frontend.contact.phone_number')" required=""/>
                                    @if($errors->has('phone'))
                                        <span class="help-block text-danger">{{$errors->first('phone')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                <div class="form-group">
                                    <textarea id="message" name="message" class="form-control custom-form" placeholder="@lang('labels.frontend.contact.message')" rows="3" required></textarea>
                                    @if($errors->has('message'))
                                        <span class="help-block text-danger">{{$errors->first('message')}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                <div class="form-group">
                                    <img src="images/captcha-img.jpg" class="img-fluid" alt="" style="height: 80px;" />
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-12">
                                <input type="submit" name="submit" id="submit" class="btn btn-primary btn-lg" value="@lang('labels.frontend.contact.send')" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- End of contact area form
        ============================================= -->


@endsection
@push('after-scripts')
    @if(config('access.captcha.registration'))
        {{ no_captcha()->script() }}
    @endif
@endpush
