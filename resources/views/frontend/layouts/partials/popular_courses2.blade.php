
<!-- Start popular course
       ============================================= -->
@if(count($popular_courses) > 0)
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <h3>@lang('labels.frontend.layouts.partials.popular_courses')</h3>
                </div>
            </div>
            <div class="row mtb-30 clearfix">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="owl-carousel owl-theme">

                        @foreach($popular_courses as $item)

                            <div class="item">
                                <div class="coursegrid clearfix">
                                    <img src="{{asset('storage/uploads/'.$item->course_image)}}" alt="" />
                                    <div class="price">
                                        @if($item->free == 1)
                                            {{trans('labels.backend.courses.fields.free')}}
                                        @else
                                            {{$appCurrency['symbol'].' '.$item->price}}
                                        @endif
                                    </div>
                                    <h6><a href="{{ route('courses.show', [$item->slug]) }}">{{$item->title}}</a></h6>
                                    <p>{{substr($item->description, 0,200).'...'}}</p>
                                    <div class="row clearfix">
                                        <div class="col-8 col-sm-7 col-md-7 col-lg-8 col-xl-8">
                                            @foreach($item->teachers as $teacher)
                                            <div class="user-img">
                                                <img src="{{$teacher->picture}}" alt="Image goes here" />
                                                <p class="username">By <span><a href="#">{{$teacher->first_name}}</a></span></p>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="col-4 col-sm-5 col-md-5 col-lg-4 col-xl-4">
                                            <ul class="subicons">
                                                <li><i class="fa fa-users"></i> {{ $item->students()->count() }}</li>
                                                <li><i class="fa fa-commenting-o"></i> {{count($item->reviews) }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- /item -->
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End popular course
        ============================================= -->
@endif