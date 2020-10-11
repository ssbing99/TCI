@extends('frontend.layouts.app'.config('theme_layout'))
@php $no_footer = true; @endphp

@section('title', trans('labels.frontend.home.title').' | '.app_name())
@section('meta_description', '')
@section('meta_keywords','')

@push("after-styles")
    <style>
        #search-course {
            padding-bottom: 125px;
        }
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

        #search-course select{
            background-color: #4273e1!important;
            color: white!important;
        }
    </style>
@endpush
@php
    $footer_data = json_decode(config('footer_data'));
@endphp
@section('content')
    @if(session()->has('alert'))
        <div class="alert alert-light alert-dismissible fade my-alert show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>{{session('alert')}}</strong>
        </div>
    @endif

    <!-- Start of slider section
     ============================================= -->
    @include('frontend.layouts.partials.slider2')

    <!-- End of slider section
            ============================================= -->


    <!-- Start popular course
        ============================================= -->
    @if($sections->popular_courses->status == 1)
        @include('frontend.layouts.partials.popular_courses2')
    @endif
    <!-- End popular course
    ============================================= -->

    <!-- Start Free Content
        ============================================= -->
    @if($sections->popular_courses->status == 1)
        @include('frontend.layouts.partials.free_content')
    @endif
    <!-- End Free Content
    ============================================= -->

    <!-- Start secound testimonial section
        ============================================= -->
    @if($sections->testimonial->status == 1)
        @include('frontend.layouts.partials.custom_testimonial');
    @endif
    <!-- End secound testimonial section
        ============================================= -->

    @if($sections->contact_us->status == 1)
        <!-- Start of contact area
        ============================================= -->

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
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control @if($errors->has('email')) border-bottom border-danger @endif" name="email" id="email" placeholder="@lang('labels.frontend.contact.email')" />
                            </div>
{{--                            <div class="form-group">--}}
{{--                                <input type="text" class="form-control @if($errors->has('address')) border-bottom border-danger @endif" name="address" id="address" placeholder="@lang('labels.frontend.contact.address')" />--}}
{{--                            </div>--}}
                            <div class="form-group">
                                <textarea class="form-control @if($errors->has('message')) border-bottom border-danger @endif" name="message" id="message" placeholder="@lang('labels.frontend.contact.message')"></textarea>
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
        <!-- End of contact area
            ============================================= -->
    @endif

    <!-- Start of footer with subscribe
        ============================================= -->
    @include('frontend.layouts.partials.footer2')
    <!-- End of footer with subscribe
            ============================================= -->

@endsection

@push('after-scripts')
    <script>
        // $('ul.product-tab').find('li:first').addClass('active');
    </script>
@endpush