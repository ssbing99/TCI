
<!-- Start popular course
       ============================================= -->
@if(count($popular_courses) > 0)
    <section class="bg-f0f1f5 clearfix">
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <h3>@lang('labels.frontend.layouts.partials.popular_courses')
                        <div class="float-right">
                            <button class="btn btn-primary filter-button" data-filter="all">All</button>
                            <button class="btn btn-outline-secondary filter-button" data-filter="beginner">Beginner</button>
                            <button class="btn btn-outline-secondary filter-button" data-filter="intermediate">Intermediate</button>
                            <button class="btn btn-outline-secondary filter-button" data-filter="advanced">Advanced</button>
                            <a class="btn btn-trans float-right" href="{{route('courses.all')}}">View All <i class="fa fa-long-arrow-right"></i></a>
                        </div>
                    </h3>
                </div>
            </div>
            <div class="row clearfix">
                <?php
                $bignCnt = 0;
                $intCnt = 0;
                $advCnt = 0;
                $text = '';
                ?>
                @foreach($popular_courses as $item)
                    <?php
                    if($item->beginner == 1 && $bignCnt <= 2){
                        $bignCnt++;
                        $text = 'beginner';
                    }elseif($item->intermediate == 1 && $intCnt <= 2){
                        $intCnt++;
                        $text = 'intermediate';
                    } elseif($item->advance == 1 && $advCnt <= 2){
                        $advCnt++;
                        $text = 'advanced';
                    }
                    ?>

                <div class="gallery_product col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3 filter <?php echo $text ?>">
                    <figure>
                        <a href="{{ route('courses.show', [$item->slug]) }}"><img src="{{asset('storage/uploads/'.$item->course_image)}}" alt="Images goes here" /></a>
                        <figcaption>
                            <a href="{{ route('courses.show', [$item->slug]) }}"><h4>{{$item->title}}</h4></a>
                            <ul class="clearfix">
                                <li>
                                    <div class="title">Instructor</div>
                                    <div class="text">
                                        <?php $key=0; ?>
                                        @foreach($item->teachers as $teacher)
                                            {{$teacher->first_name}}
                                            @if($key > 0) {{','}} @endif
                                            <?php $key++ ?>
                                        @endforeach
                                    </div>
                                </li>
                                <li>
                                    <div class="title">Duration</div>
                                    <div class="text">{{$item->duration}} Days</div>
                                </li>
                                <li>
                                    <div class="title">Course Price</div>
                                    <div class="text">
                                        @if($item->free == 1)
                                            {{trans('labels.backend.courses.fields.free')}}
                                        @else
                                            {{$appCurrency['symbol'].' '.$item->price}}
                                        @endif
                                    </div>
                                </li>
                            </ul>
                        </figcaption>
                    </figure>
                </div>
                    <!-- /item -->
                @endforeach
{{--                <div class="gallery_product col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3 filter intermediate">--}}
{{--                    <figure>--}}
{{--                        <img src="{{asset("assets_new/images/course-img-2.jpg")}}" alt="Images goes here" />--}}
{{--                        <figcaption>--}}
{{--                            <h4>Capturing Breathetaking Landscape</h4>--}}
{{--                            <ul class="clearfix">--}}
{{--                                <li>--}}
{{--                                    <div class="title">Instructor</div>--}}
{{--                                    <div class="text">Gina Genis</div>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <div class="title">Duration</div>--}}
{{--                                    <div class="text">3 Months</div>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <div class="title">Course Price</div>--}}
{{--                                    <div class="text">$149</div>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </figcaption>--}}
{{--                    </figure>--}}
{{--                </div>--}}
{{--                <div class="gallery_product col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3 filter advanced">--}}
{{--                    <figure>--}}
{{--                        <img src="{{asset("assets_new/images/course-img-3.jpg")}}" alt="Images goes here" />--}}
{{--                        <figcaption>--}}
{{--                            <h4>Capturing Breathetaking Landscape</h4>--}}
{{--                            <ul class="clearfix">--}}
{{--                                <li>--}}
{{--                                    <div class="title">Instructor</div>--}}
{{--                                    <div class="text">Gina Genis</div>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <div class="title">Duration</div>--}}
{{--                                    <div class="text">3 Months</div>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <div class="title">Course Price</div>--}}
{{--                                    <div class="text">$149</div>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </figcaption>--}}
{{--                    </figure>--}}
{{--                </div>--}}
{{--                <div class="gallery_product col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3 filter beginner">--}}
{{--                    <figure>--}}
{{--                        <img src="{{asset("assets_new/images/course-img-1.jpg")}}" alt="Images goes here" />--}}
{{--                        <figcaption>--}}
{{--                            <h4>Capturing Breathetaking Landscape</h4>--}}
{{--                            <ul class="clearfix">--}}
{{--                                <li>--}}
{{--                                    <div class="title">Instructor</div>--}}
{{--                                    <div class="text">Gina Genis</div>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <div class="title">Duration</div>--}}
{{--                                    <div class="text">3 Months</div>--}}
{{--                                </li>--}}
{{--                                <li>--}}
{{--                                    <div class="title">Course Price</div>--}}
{{--                                    <div class="text">$149</div>--}}
{{--                                </li>--}}
{{--                            </ul>--}}
{{--                        </figcaption>--}}
{{--                    </figure>--}}
{{--                </div>--}}
            </div>
        </div>
    </section>
    <!-- End popular course
        ============================================= -->
@endif