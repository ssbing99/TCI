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

    <section class="gift-bg">
        <div class="container">
            <div class="row clearfix">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <img src="{{asset("assets_new/images/gift.png")}}" alt="Gift">
                    <p>A virtual classroom / social-interactive experience... As a TCI student, you’ll receive valuable critiques and advice from your instructor, timely answers to your questions and be able to discuss all that is happening on your course with fellow classmates. Sign up for a no-strings-attached account, enroll for a course and you are set to begin!</p>
                    <center><a class="btn btn-outline-primary btn-lg" href="gifts.html">Gift a Course</a></center>
                </div>
            </div>
        </div>
    </section>

    <!-- Start Free Content
        ============================================= -->
{{--    @if($sections->popular_courses->status == 1)--}}
{{--        @include('frontend.layouts.partials.free_content')--}}
{{--    @endif--}}
    <!-- End Free Content
    ============================================= -->

    <!-- Start secound testimonial section
        ============================================= -->
{{--    @if($sections->testimonial->status == 1)--}}
{{--        @include('frontend.layouts.partials.custom_testimonial');--}}
{{--    @endif--}}
    <!-- End secound testimonial section
        ============================================= -->

    <!-- Start teacher section
        ============================================= -->
        @include('frontend.layouts.partials.our_teachers');
    <!-- End teacher section
        ============================================= -->

    <!-- Start recent blogs section
        ============================================= -->
        @include('frontend.layouts.partials.recent_blogs');
    <!-- End recent blogs section
        ============================================= -->

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