@extends('frontend.layouts.app'.config('theme_layout'))

@section('title', ($course->meta_title) ? $course->meta_title : app_name() )
@section('meta_description', $course->meta_description)
@section('meta_keywords', $course->meta_keywords)

@push('after-styles')
    <style>
        .leanth-course.go {
            right: 0;
        }
        .video-container iframe{
            max-width: 100%;
        }

    </style>
    <link rel="stylesheet" href="https://cdn.plyr.io/3.5.3/plyr.css"/>

@endpush

@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    <div class="banner custom-banner-bg">
        <div class="container">
            <div class="page-heading text-sm-center">
                @lang('labels.frontend.course.courses')
            </div>
        </div>
    </div>
    <!-- End of breadcrumb section
        ============================================= -->

    <!-- Start of course details section
        ============================================= -->
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    @if(session()->has('success'))
                        <div class="alert alert-dismissable alert-success fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{session('success')}}
                        </div>
                    @endif
                    <div class="pagetitle clearfix">
                        {{$course->title}}
{{--                        @if($course->trending == 1)--}}
{{--                            <span class="trend-badge text-uppercase bold-font"><i--}}
{{--                                        class="fas fa-bolt"></i> @lang('labels.frontend.badges.trending')</span>--}}
{{--                        @endif--}}
                    </div>
                </div>
                <div class="col-12">
                    <ul class="details clearfix">

                        <!-- CHECK FOR AUTH and display -->

                        <li class="clearfix">
                            @if($course->free == 1)
                                <span> {{trans('labels.backend.courses.fields.free')}}</span>
                            @else
                            <div class="txtlabel clearfix">@lang('labels.frontend.course.price')
                                @if($course->free == 1)
                                    <span> {{trans('labels.backend.courses.fields.free')}}</span>
                                @else
                                    <span class="courseprice">{{$appCurrency['symbol'].' '.$course->price}}</span>
                                @endif
                            </div>
                            @endif
                        </li>

                        <li class="clearfix">
                            <div class="txtlabel clearfix">@lang('labels.frontend.course.category')
                                <span class="category">
                                    <a href="{{route('courses.category',['category'=>$course->category->slug])}}"
                                       target="_blank">{{$course->category->name}}</a>
                                </span>
                            </div>
                        </li>
                        <li class="clearfix">
                            @foreach($course->teachers as $key=>$teacher)
                                @php $key++ @endphp
                            <img src="{{$teacher->picture}}" class="instructor-pic" alt="Image goes here" />
                            <div class="txtlabel clearfix">@lang('labels.frontend.course.teacher')
                                <span class="instructorname">
                                    <a href="{{route('teachers.show',['id'=>$teacher->id])}}" target="_blank">
                                        {{$teacher->full_name}}@if($key < count($course->teachers )), @endif
                                    </a>
                                </span>
                            </div>
                            @endforeach
                        </li>
                        <li class="clearfix"><div class="txtlabel clearfix">@lang('labels.frontend.course.ratings')
                                <span class="stars">
                                    @for($i=1; $i<=(int)$course->rating; $i++)
                                        <i class="fa fa-star"></i>
                                    @endfor
                                </span>
                            </div>
                        </li>
                        <li class="clearfix">
{{--                            <a href="cart.html" class="btn btn-theme btn-block btn-lg">BUY NOW</a>--}}
                            @if (!$purchased_course)
                                @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                    <button class="btn btn-theme btn-block btn-lg"
                                            type="submit">@lang('labels.frontend.course.added_to_cart')
                                    </button>
                                @elseif(!auth()->check())
                                    @if($course->free == 1)
                                        <a id="openLoginModal"
                                           class="btn btn-theme btn-block btn-lg text-center text-white text-uppercase"
                                           data-target="#myModal" href="#">@lang('labels.frontend.course.get_now') </a>
                                    @else
                                        <a id="openLoginModal"
                                           class="btn btn-theme btn-block btn-lg text-center text-white text-uppercase"
                                           data-target="#myModal" href="#">@lang('labels.frontend.course.buy_now') </a>

                                        <a id="openLoginModal"
                                           class="btn btn-theme btn-block btn-lg bg-dark text-center text-white text-uppercase"
                                           data-target="#myModal" href="#">@lang('labels.frontend.course.add_to_cart') <i
                                                    class="fa fa-shopping-bag"></i></a>
                                    @endif

                                @elseif(auth()->check() && (auth()->user()->hasRole('student')))
                                    @if($course->free == 1)
                                        <form action="{{ route('cart.getnow') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                            <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                            <button class="btn btn-theme btn-block btn-lg text-center text-white text-uppercase"
                                                    href="#">@lang('labels.frontend.course.get_now')</button>
                                        </form>
                                    @else
                                        <form action="{{ route('cart.checkout') }}" method="POST" class="mb-2">
                                            @csrf
                                            <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                            <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                            <button class="btn btn-theme btn-block btn-lg text-center text-white text-uppercase"
                                                    href="#">@lang('labels.frontend.course.buy_now')</button>
                                        </form>
                                        <form action="{{ route('cart.addToCart') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                            <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                            <button type="submit"
                                                    class="btn btn-theme btn-block btn-lg bg-dark text-center text-white text-uppercase ">
                                                @lang('labels.frontend.course.add_to_cart') <i
                                                        class="fa fa-shopping-bag"></i></button>
                                        </form>

                                    @endif

                                @else
                                    <h6 class="alert alert-danger"> @lang('labels.frontend.course.buy_note')</h6>
                                @endif
                            @else

                                @if($continue_course)
                                    <a href="{{route('lessons.show',['course_id' => $course->id,'slug'=>$continue_course->model->slug])}}"
                                       class="btn btn-theme btn-block btn-lg text-center text-white text-uppercase">

                                        @lang('labels.frontend.course.continue_course')

                                        <i class="fa fa-arow-right"></i></a>
                                @endif
                            @endif

                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="col-12">
                    <div class="featureshead clearfix">@lang('labels.frontend.course.course_features')</div>
                </div>
            </div>
            <div class="row mtb-15 clearfix">
                <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <div class="featuregrid clearfix">
                        <p><i class="fa fa-file-text-o"></i> @lang('labels.frontend.course.lectures')<span>{{$course->chapterCount()}}</span></p>
                    </div>
                </div>
                <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <div class="featuregrid clearfix">
                        <p><i class="fa fa-puzzle-piece"></i> @lang('labels.frontend.course.quizzes')<span>{{$course->quizCount()}}</span></p>
                    </div>
                </div>
                <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <div class="featuregrid clearfix">
                        <p><i class="fa fa-clock-o"></i> @lang('labels.frontend.course.duration')<span>{{$course->duration > 0 ? $course->duration : '0'}} @lang('labels.frontend.layouts.partials.hours')</span></p>
                    </div>
                </div>
                <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <div class="featuregrid clearfix">
                        <p><i class="fa fa-graduation-cap"></i> @lang('labels.frontend.course.skill_level')<span>{{isset($course->skill_level) ? mb_strtoupper($course->skill_level) : '-'}}</span></p>
                    </div>
                </div>
                <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <div class="featuregrid clearfix">
                        <p><i class="fa fa-globe"></i> @lang('labels.frontend.course.language')<span>English</span></p>
                    </div>
                </div>
                <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <div class="featuregrid clearfix">
                        <p><i class="fa fa-user"></i> @lang('labels.frontend.course.students')<span>{{ $course->students()->count() }}</span></p>
                    </div>
                </div>
                <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <div class="featuregrid clearfix">
                        <p><i class="fa fa-certificate"></i> @lang('labels.frontend.course.certificate')<span>Yes</span></p>
                    </div>
                </div>
                <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                    <div class="featuregrid clearfix">
                        <p><i class="fa fa-file-text"></i> @lang('labels.frontend.course.assessments')<span>{{$course->quizCount() > 0? 'Yes':'No'}}</span></p>
                    </div>
                </div>
            </div>
            @if($course->mediaVideo && $course->mediavideo->count() > 0)
                <div class="row mtb-15 clearfix">
                    @if($course->mediavideo != "")
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="video-container mb-5" data-id="{{$course->mediavideo->id}}">
                                @if($course->mediavideo->type == 'youtube')


                                    <div id="player" class="js-player" data-plyr-provider="youtube"
                                         data-plyr-embed-id="{{$course->mediavideo->file_name}}"></div>
                                @elseif($course->mediavideo->type == 'vimeo')
                                    <div id="player" class="js-player" data-plyr-provider="vimeo"
                                         data-plyr-embed-id="{{$course->mediavideo->file_name}}"></div>
                                @elseif($course->mediavideo->type == 'upload')
                                    <video poster="" id="player" class="js-player" playsinline controls>
                                        <source src="{{$course->mediavideo->url}}" type="video/mp4"/>
                                    </video>
                                @elseif($course->mediavideo->type == 'embed')
                                    {!! $course->mediavideo->url !!}
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endif
{{--            <div class="row mtb-15 clearfix">--}}
{{--                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">--}}
{{--                    <div class="itemtitle clearfix">Watch me do a quick landscape asssessment for this farm</div>--}}
{{--                    <iframe width="100%" height="360" src="{{$course->mediavideo->url}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>--}}
{{--                    <iframe width="100%" height="360" src="https://www.youtube.com/embed/0T6NtiCyxKA" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>--}}
{{--                </div>--}}
{{--                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">--}}
{{--                    <div class="itemtitle clearfix">Once you register this is how you gain access to the course</div>--}}
{{--                    <iframe width="100%" height="360" src="https://www.youtube.com/embed/0T6NtiCyxKA" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>--}}
{{--                </div>--}}
{{--                <div class="col-12">--}}
{{--                    <p class="content clearfix">{!! $course->description !!}</p>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="row mtb-15 clearfix">
                <div class="col-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                    <div class="grey-bg p-20 clearfix">
                        <div class="sectiontitle clearfix">@lang('labels.frontend.course.curriculum')</div>
                        @if(count($lessons) == 0)

                            <ul class="lecture clearfix">
                                <li><span>@lang('labels.frontend.course.no_lesson')</span></li>
                            </ul>
                        @endif
                        @if(count($lessons)  > 0)
                            @php $count = 0; @endphp
                            <ul class="lecture clearfix">
                            @foreach($lessons as $key=> $lesson)
                                @if($lesson->model && $lesson->model->published == 1)
                                    @php
                                        $count++;
                                        $auth_display = '<li><i class="fa fa-file-o"></i>'. sprintf("%2d", $count).'.'.' <span>';
                                    @endphp

                                    @if(auth()->check())
                                        @if(in_array($lesson->model->id,$completed_lessons))

                                                @php
                                                    $auth_display .= '<a href="'.route('lessons.show',['course_id' => $lesson->course->id,'slug'=>$lesson->model->slug]).'">'.$lesson->model->title.'</a>';
                                                @endphp
{{--                                                <li><i class="fa fa-file-o"></i>{{ sprintf("%2d", $count).'.'}}&nbsp;<span>--}}
{{--                                                        <a href="{{route('lessons.show',['course_id' => $lesson->course->id,'slug'=>$lesson->model->slug])}}">--}}
{{--                                                            {{$lesson->model->title}}</a></span><span class="duration font-weight-bold">@lang('labels.frontend.course.completed')</span>--}}
{{--                                                </li>--}}
                                        @else
                                            @php $auth_display .= $lesson->model->title @endphp
                                        @endif
                                    @else
                                            @php $auth_display .= $lesson->model->title @endphp
                                    @endif
                                        @php
                                            $auth_display .= '</span>';

                                            if($lesson->model_type == 'App\Models\Test'){
                                                $auth_display .=' <span class="badge badge-success">'.trans('labels.frontend.course.test').'</span>';
                                            }

                                            if($lesson->model->live_lesson){
                                                $auth_display .=' <span class="badge badge-success">'.trans('labels.frontend.course.live_lesson').'</span>';
                                            }

                                            $auth_display .='<span class="duration">2m</span>';

                                            if($lesson->model->live_lesson){

                                            }
                                        @endphp

                                        {!! $auth_display !!}

                                        @if($lesson->model->live_lesson)
                                            <div class="card mt-3 border">
                                                <div class="card-header">
                                                    <h6 class="text-black-50"><b>@lang('labels.frontend.course.available_slots')</b></h6>
                                                </div>
                                                <div class="card-body border-dark">
                                                    <ul>
                                                @forelse($lesson->model->liveLessonSlots as $slot)

                                                    <li>
                                                        @lang('labels.frontend.course.date') {{ $slot->start_at->format('d-m-Y h:i A') }}
                                                    </li>
                                                @empty
                                                @endforelse
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif

                                        {!! '</li>' !!}

                                @endif
                            @endforeach
                            </ul>
                        @endif
{{--                        <ul class="lecture clearfix">--}}
{{--                            <li><i class="fa fa-file-o"></i>Lecture 1.1 <span><a href="#">Introduction</a></span><span class="duration">2m</span></li>--}}
{{--                            <li><i class="fa fa-file-o"></i>Lecture 1.2 <span><a href="#">Introduction</a></span><span class="duration">2m</span></li>--}}
{{--                            <li><i class="fa fa-file-o"></i>Lecture 1.3 <span><a href="#">Introduction</a></span><span class="duration">2m</span></li>--}}
{{--                            <li><i class="fa fa-file-o"></i>Lecture 1.4 <span><a href="#">Introduction</a></span><span class="duration">2m</span></li>--}}
{{--                            <li><i class="fa fa-file-o"></i>Lecture 1.5 <span><a href="#">Introduction</a></span><span class="duration">2m</span></li>--}}
{{--                        </ul>--}}
                    </div>
                    <div class="featureshead clearfix">@lang('labels.frontend.course.ratings_reviews')</div>
                    @if(count($course->reviews) > 0)
                        <ul class="ratings clearfix">
                            @foreach($course->reviews as $item)
                                <li>
                                    <div class="ratingsbox clearfix">
                                        <img src="{{$item->user->picture}}" alt="" />
                                        <div class="content">
                                            <div class="name">{{$item->user->full_name}}<span>{{empty($item->user->country)? '-':$item->user->country}}</span></div>
                                            @if(auth()->check() && ($item->user_id == auth()->user()->id))
                                                <div>
                                                    <a href="{{route('courses.review.edit',['id'=>$item->id])}}"
                                                       class="mr-2">@lang('labels.general.edit')</a>
                                                    <a href="{{route('courses.review.delete',['id'=>$item->id])}}"
                                                       class="text-danger">@lang('labels.general.delete')</a>
                                                </div>

                                            @endif
                                            <div class="rating-star clearfix">
                                                @for($i=1; $i<=(int)$item->rating; $i++)
                                                    <i class="fa fa-star"></i>
                                                @endfor
                                            </div>
                                            <p class="reviewtxt">
                                                {{$item->content}}
                                                <span>{{$item->created_at->diffforhumans()}}</span>
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <h6> @lang('labels.frontend.course.no_reviews_yet')</h6>
                    @endif

                    <br/>
                    <!-- Rating form -->
                    <!-- =========================== -->

                    @if ($purchased_course)
                        @if(isset($review) || ($is_reviewed == false))
                            @php
                                if(isset($review)){
                                    $route = route('courses.review.update',['id'=>$review->id]);
                                }else{
                                   $route = route('courses.review',['id'=> $course->id]);
                                }
                            @endphp
                            <div class="row clearfix">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <h5 style="color: #333;">@lang('labels.frontend.course.add_reviews')</h5>
                                    <div class="clearfix"></div>
                                    <form class="mtb-30" action="{{$route}}" method="POST" data-lead="Residential">
                                        @csrf
                                        <input type="hidden" name="rating" id="rating">

                                        <div class="form-group">
                                            <label>@lang('labels.frontend.course.your_rating'): </label>
                                            <div class="rating">
                                                <label>
                                                    <input type="radio" name="stars" value="1"/>
                                                    <span class="icon"><i class="fa fa-star"></i></span>
                                                </label>
                                                <label>
                                                    <input type="radio" name="stars" value="2"/>
                                                    <span class="icon"><i class="fa fa-star"></i></span>
                                                    <span class="icon"><i class="fa fa-star"></i></span>
                                                </label>
                                                <label>
                                                    <input type="radio" name="stars" value="3"/>
                                                    <span class="icon"><i class="fa fa-star"></i></span>
                                                    <span class="icon"><i class="fa fa-star"></i></span>
                                                    <span class="icon"><i class="fa fa-star"></i></span>
                                                </label>
                                                <label>
                                                    <input type="radio" name="stars" value="4"/>
                                                    <span class="icon"><i class="fa fa-star"></i></span>
                                                    <span class="icon"><i class="fa fa-star"></i></span>
                                                    <span class="icon"><i class="fa fa-star"></i></span>
                                                    <span class="icon"><i class="fa fa-star"></i></span>
                                                </label>
                                                <label>
                                                    <input type="radio" name="stars" value="5"/>
                                                    <span class="icon"><i class="fa fa-star"></i></span>
                                                    <span class="icon"><i class="fa fa-star"></i></span>
                                                    <span class="icon"><i class="fa fa-star"></i></span>
                                                    <span class="icon"><i class="fa fa-star"></i></span>
                                                    <span class="icon"><i class="fa fa-star"></i></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <textarea class="form-control @if($errors->has('review')) border-bottom border-danger @endif" name="review" id="review" placeholder="@lang('labels.frontend.course.message')">@if(isset($review)){{$review->content}} @endif</textarea>
                                            @if($errors->has('review'))
                                                <span class="help-block text-danger">{{ $errors->first('review', ':message') }}</span>
                                            @endif
                                        </div>
                                        <button type="submit" name="submit" id="submit" class="btn btn-primary btn-lg" value="Submit">@lang('labels.frontend.course.add_review_now')</button>
                                    </form>
                                </div>
                            </div>

                    @endif
                @endif

                    <!-- End Rating form -->
                    <!-- =========================== -->
                </div>
                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    @if(auth()->check())
                        @if($course->course_image != "")
                            <img src="{{asset('storage/uploads/'.$course->course_image)}}"
                                 alt="" class="img-full">
                        @endif
                        <div class="theme-bg clearfix">
                            <div class="progresstxt clearfix">{{count($completed_lessons)}} of {{count($lessons)}} completed</div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: {{$course_progress_perc}}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @endif

                        @foreach($course->teachers as $key=>$teacher)
                    <div class="bg-grey mtb-30 clearfix">
                        <div class="sectiontitle clearfix">Instructor</div>
                        <div class="instructorname clearfix">
                            <img src="{{$teacher->picture}}" align="Instructor pic" />
                            <div class="insname">
                                {{$teacher->full_name}}
                            </div>
                        </div>
                        <p class="txt clearfix">
                            @if(!isset($teacher->description))
                                No description yet.
                            @else
                                {{$teacher->description}}
                            @endif
                        </p>
                    </div>
                        @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- End of course details section
        ============================================= -->

    <!-- Start of BUNDLE course details section
        ============================================= -->

    <!-- End of BUNDLE course details section
        ============================================= -->

@endsection

@push('after-scripts')
    <script src="https://cdn.plyr.io/3.5.3/plyr.polyfilled.js"></script>

    <script>
        const player = new Plyr('#player');

        $(document).on('change', 'input[name="stars"]', function () {
            $('#rating').val($(this).val());
        })
                @if(isset($review))
        var rating = "{{$review->rating}}";
        $('input[value="' + rating + '"]').prop("checked", true);
        $('#rating').val(rating);
        @endif
    </script>
@endpush
