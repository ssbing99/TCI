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

    <section class="bg-f9f9f9 clearfix">
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <div class="test-title clearfix">Testimonial</div>
                    <div id="testcarousel" class="carousel slide" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="test-items clearfix">
                                    <img src="{{asset('assets_new/images/quote.jpg')}}" alt="Images goes here" />
                                    <p class="clearfix">A great introduction to the genre of street photography, taught by legendary photographer, "Akash." I felt Akash's enthusiasm and passion for capturing life on the street throughout this online-interactive course. Akash showed me how to "see" photos amid the chaos, how to capture the emotion and energy of life on the street and how to edit my images for greatest impact. An online course that will build your confidence, hone your skills, and load your photography with an enthusiastic vision. Thanks G.M.B. Akash. You're the master.</p>
                                    <div class="test-name">
                                        <img src="{{asset('assets_new/images/paul-lavergne.jpg')}}" alt="Images goes here" />
                                        <p>Paul Lavergne<span>Street Photography with G.M.B.</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="test-items clearfix">
                                    <img src="{{asset('assets_new/images/quote.jpg')}}" alt="Images goes here" />
                                    <p class="clearfix">A great introduction to the genre of street photography, taught by legendary photographer, "Akash." I felt Akash's enthusiasm and passion for capturing life on the street throughout this online-interactive course. Akash showed me how to "see" photos amid the chaos, how to capture the emotion and energy of life on the street and how to edit my images for greatest impact. An online course that will build your confidence, hone your skills, and load your photography with an enthusiastic vision. Thanks G.M.B. Akash. You're the master.</p>
                                    <div class="test-name">
                                        <img src="{{asset('assets_new/images/paul-lavergne.jpg')}}" alt="Images goes here" />
                                        <p>Paul Lavergne<span>Street Photography with G.M.B.</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#testcarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"><i class="fa fa-chevron-left"></i></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#testcarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"><i class="fa fa-chevron-right"></i></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection