@extends('frontend.layouts.app'.config('theme_layout'))

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
    <header>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <h1>How it Works</h1>
                </div>
            </div>
        </div>
    </header>

    <!-- End of breadcrumb section
        ============================================= -->

    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <p class="course-txt clearfix">
                        Timely personal interaction and inspiring feedback are hugely important to the learning process - and this is just what you’ll get at The Compelling Image.
                        <span>Your TCI enrollment includes :</span>
                    </p>
                    <ul class="course-list clearfix">
                        <li>Clear, enjoyable to read, and richly illustrated course lessons, downloadable for your convenience.</li>
                        <li>Lesson assignments to practice your new skills - using subjects and situations accessible to you, wherever you live.</li>
                        <li>Friendly and constructive critiques of your work by our award-winning, international instructors, delivered to your Student Area within 48 hours; your questions answered within 24 hours.</li>
                        <li>Valuable tips and advice on ways to improve your photography.</li>
                    </ul>
                    <p class="course-txt clearfix"><span>Getting started is fast and easy, simply :</span></p>
                    <ul class="course-list clearfix">
                        <li>Sign up for a free TCI account.</li>
                        <li>Choose from our list of inspiring courses.</li>
                        <li>Make your secure payment using Pay Pal or major credit card.</li>
                        <li>Receive quick confirmation of your payment, log into your Student Area and you’re ready to begin!</li>
                    </ul>
                    <p class="course-txt clearfix">
                        <span>Our Guarantee</span>
                        We’re certain you’ll enjoy and greatly benefit from your online-interactive learning experience at TCI. However, if you are not satisfied for any reason, TCI will immediately refund your full payment if notified at support@thecompellingimage.com within the first week of your enrollment. Once the second lesson becomes available in your Student Area, no refund will be issued.
                    </p>
                    <a href="{{route('courses.all')}}" class="btn btn-primary btn-md mt-20">ENROLL NOW</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Start recent testimonial section
        ============================================= -->
    @include('frontend.layouts.partials.custom_testimonial');
    <!-- End recent blogs section
        ============================================= -->
@endsection