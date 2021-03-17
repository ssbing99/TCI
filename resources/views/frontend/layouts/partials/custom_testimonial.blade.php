
<!-- Start testimonial
       ============================================= -->
@php $testi_cnt = 0; @endphp

<section class="bg-f9f9f9 clearfix">
    <div class="container">
        <div class="row clearfix">
            <div class="col-12">
                <div class="test-title clearfix">Testimonial</div>
                <div id="testcarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        @if($testimonials->count() > 0)

                            @foreach($testimonials as $item)

                                <div class="carousel-item @if($testi_cnt == 0) active @endif">
                                    <div class="test-items clearfix">
                                        <img src="{{asset('assets_new/images/quote.jpg')}}" alt="Images goes here" />
                                        <p class="clearfix">{{$item->content}}</p>
                                        <div class="test-name">
                                            <img src="{{asset('assets_new/images/paul-lavergne.jpg')}}" alt="" />
{{--                                            <img src="{{$item->image}}" alt="" />--}}
                                            <p>{{$item->name}}<span>{{$item->occupation}}</span></p>
                                        </div>
                                    </div>
                                </div>
                                @php $testi_cnt++; @endphp
                            @endforeach
                        @endif

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

    <!-- End testimonial
        ============================================= -->