@extends('frontend.layouts.app'.config('theme_layout'))

@section('title', ($page->meta_title) ? $page->meta_title : app_name())
@section('meta_description', ($page->meta_description) ? $page->meta_description :'' )
@section('meta_keywords', ($page->meta_keywords) ? $page->meta_keywords : app_name())

@push('after-styles')
    @if(config('theme_layout') != 5)
        <style>
        .content img {
            margin: 10px;
        }
        .about-page-section ul{
            padding-left: 20px;
            font-size: 20px;
            color: #333333;
            font-weight: 300;
            margin-bottom: 25px;
        }
    </style>
    @else
        <style>
            .content p {
                font-family: "Roboto-Regular", Arial;
                font-size: 16px;
                text-align: left;
                color: #666;
                display: block;
                line-height: 22px;
                margin: 15px 0;
                padding: 0;
            }
        </style>
    @endif
@endpush

@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    @if(config('theme_layout') == 5)
        <header>
            <div class="container">
                <div class="row clearfix">
                    <div class="col-12">
                        <h1>{{$page->title}}</h1>
                    </div>
                </div>
            </div>
        </header>
    @else
        <section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style">
            <div class="blakish-overlay"></div>
            <div class="container">
                <div class="page-breadcrumb-content text-center">
                    <div class="page-breadcrumb-title">
                        <h2 class="breadcrumb-head black bold">{{env('APP_NAME')}} <span>{{$page->title}}</span></h2>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- End of breadcrumb section
        ============================================= -->

    @if(config('theme_layout') == 5)

        <section>
            <div class="container">
                <div class="row clearfix">
                    <div class="col-12 col-sm-8 col-md-9 col-lg-9 col-xl-9 content">
                        {!! $page->content !!}
                    </div>
                    @if($page->sidebar == 1 && config('theme_layout') == 5)
                        @include('frontend.layouts.partials.right-sidebar2')
                    @endif
                </div>
            </div>
        </section>

    @else
    <section id="about-page" class="about-page-section">
        <div class="container">
            <div class="row">
                <div class="@if($page->sidebar == 1) col-md-9 @else col-md-12 @endif ">
                    <div class="about-us-content-item">
                        @if($page->image != "")
                        <div class="about-gallery w-100 text-center">
                            <div class="about-gallery-img d-inline-block float-none">
                                <img src="{{asset('storage/uploads/'.$page->image)}}" alt="">
                            </div>
                        </div>
                    @endif
                    <!-- /gallery -->

                        <div class="about-text-item">
                            <div class="section-title-2  headline text-left">
                                <h2>{{$page->title}}</h2>
                            </div>
                           {!! $page->content !!}
                        </div>
                        <!-- /about-text -->
                    </div>
                </div>
                @if($page->sidebar == 1 && config('theme_layout') != 5)
                    @include('frontend.layouts.partials.right-sidebar')
                @endif
            </div>
        </div>
    </section>

    @endif
@endsection