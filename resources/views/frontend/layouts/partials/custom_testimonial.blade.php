
<!-- Start testimonial
       ============================================= -->
@php $testi_cnt = 0; @endphp

<section>
    <div class="container">
        <div class="row clearfix">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><h3>What Our <span>Student Says</span></h3></div>
        </div>
        <div class="row clearfix">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div id="studenttestimonial" class="carousel slide" data-ride="carousel">
                    <!-- Carousel items -->
                    <div class="carousel-inner">

                            @if($testimonials->count() > 0)

                                @foreach($testimonials as $item)

                                    @if($testi_cnt == 0 || $testi_cnt % 2  == 0)
                                    <div class="carousel-item @if($testi_cnt == 0) active @endif">
                                        <div class="row">
                                    @endif
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                            <div class="testbox">
                                                <p>{{$item->name}}</p>
                                                <div class="nameholder">
                                                    <img src="{{asset("assets_new/images/test-user.jpg")}}" alt="Chef Pic">
                                                </div>
                                                <blockquote>{{$item->content}}</blockquote>
                                            </div>
                                        </div>

                                    @php $testi_cnt++; @endphp

                                    @if($testi_cnt % 2  == 0)
                                        </div>
                                        <!--.row-->
                                    </div>
                                    @endif

                                @endforeach
                            @endif

                    </div>
                    <!--.carousel-inner-->
                    <a class="carousel-control-prev" href="#studenttestimonial" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"><i class="fa fa-chevron-left"></i></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#studenttestimonial" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"><i class="fa fa-chevron-right"></i></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
    <!-- End testimonial
        ============================================= -->