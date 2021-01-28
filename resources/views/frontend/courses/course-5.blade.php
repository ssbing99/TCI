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
    <header>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <h1>{{$course->title}}</h1>
                </div>
            </div>
        </div>
    </header>
    <!-- End of breadcrumb section
        ============================================= -->

    <!-- Start of course details section
        ============================================= -->
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                    <div class="courses-img clearfix">
                        <img src="{{asset('storage/uploads/'.$course->course_image)}}" alt="Images goes here" />
                        <div class="course-overlay clearfix">
                            @foreach($course->teachers as $key=>$teacher)
                                @php $key++ @endphp
                                @if($key > 1)
                                    <br/>
                                    @endif
                                <img src="{{$teacher->picture}}" alt="Image goes here" />
                                <div class="insname">{{$teacher->full_name}}</div>
                                @if($key == 1)
                                <div class="inscat">Category : {{$course->getLevelAsCategory()}}</div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @if($course->mediaVideo && $course->mediavideo->count() > 0)
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
                    @endif
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="information-tab" data-toggle="tab" href="#information" role="tab" aria-controls="information" aria-selected="true"><i class="fa fa-info-circle"></i> Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="instructor-tab" data-toggle="tab" href="#instructor" role="tab" aria-controls="instructor" aria-selected="false"><i class="fa fa-graduation-cap"></i> Instructor</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="reviews-tab" data-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="false"><i class="fa fa-comment"></i> Reviews</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="information" role="tabpanel" aria-labelledby="information-tab">
                            <p class="course-txt clearfix">
                                <span>Course Brief :</span>
                            </p>
                            {!! $course->description !!}
{{--                            <p class="course-txt clearfix">--}}
{{--                                <span>Course Brief :</span>--}}
{{--                                You don't need to be a photojournalist or professional documentary photographer to create engaging photo essays. Photo essays can showcase any subject, from nature, to portraiture, to wedding shots, family outings, and vacations. So, whether you’re a keen amateur photographer, or an aspiring professional, a well-crafted photo essay is a brilliant way to present a story or even in narrative visual form. But that's not all, producing photo essays is a great way to learn and apply new skills as a photographer, and to bring new enthusiasm to your photography.<br />--}}
{{--                                In this online and interactive 4-lesson / 4-assignment course, mentored by award-winning professional photographer David Bathgate, you’ll be guided through the entire process of creating your own photo essay narrative. David will be there every step of the way - to answer your questions, critique your work, and offer constructive advice for your success.--}}
{{--                                <span>Upon completion of this course, you will be able to :</span>--}}
{{--                            </p>--}}
                        </div>
                        <div class="tab-pane fade" id="instructor" role="tabpanel" aria-labelledby="instructor-tab">
                            @foreach($course->teachers as $key=>$teacher)
                                <p class="course-txt clearfix">
                                    <span>Instructor : {{$teacher->full_name}}</span>
                                    {!! $teacher->description !!}
                                </p>

                                @if($teacher->courses->count() > 0)
                                    <p class="course-txt clearfix">
                                        <span>Currently Teaching</span>
                                    </p>
                                    <ul class="course-list clearfix">
                                        @foreach($teacher->courses as $item)
                                            <li><a href="{{ route('courses.show', [$item->slug]) }}">{{$item->title}}</a></li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endforeach

                        </div>

                        <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                            <p class="course-txt clearfix"><span>Reviews :</span></p>
                            @if(count($course->reviews) > 0)
                                @foreach($course->reviews as $item)
                                    <blockquote class="blockquote">
                                        <i class="fa fa-quote-left"></i>
                                        <p>{!! nl2br($item->content) !!}</p>
                                        <footer class="blockquote-footer">By {{$item->user->full_name}}</footer>
                            </blockquote>
                                @endforeach
                            @else
                                <blockquote class="blockquote">
                                    @lang('labels.frontend.course.no_reviews_yet')
                                </blockquote>
                            @endif
{{--                            <blockquote class="blockquote">--}}
{{--                                <i class="fa fa-quote-left"></i>--}}
{{--                                <p>A great course, and one I enjoyed very much. I Learned a lot and already working on my third photo essay about my large extended family. Thanks David for the advice, guidance, patience and valuable insights.</p>--}}
{{--                                <footer class="blockquote-footer">By Sejuti Basu</footer>--}}
{{--                            </blockquote>--}}
{{--                            <blockquote class="blockquote">--}}
{{--                                <i class="fa fa-quote-left"></i>--}}
{{--                                <p>I took this course to document the workings of our family's olive production business in California. The work I produced went right to our company's brochure. A learned the workings of how a photo story is made and the kinds of images that express it best. It was my second course here with David Bathgate and I would highly recommend both. This is not a course for JUST those wanting to become professionals, it's one that anyone will benefit from and enjoy. I know I did!</p>--}}
{{--                                <footer class="blockquote-footer">By Antoinette Addison</footer>--}}
{{--                            </blockquote>--}}
{{--                            <blockquote class="blockquote">--}}
{{--                                <i class="fa fa-quote-left"></i>--}}
{{--                                <p>A truly inspirational course! Instruction went step-by-step through the process of creating a powerful and dynamic visual story. As its instructor, David was excellent in his insight, advice and support. Top Notch all the way!</p>--}}
{{--                                <footer class="blockquote-footer">By Richard Gough</footer>--}}
{{--                            </blockquote>--}}
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <aside>
                        <ul class="course-details clearfix">
                            <li>
                                <i class="fa fa-clock-o"></i>
                                <p>Duration<span>{{$course->duration > 0 ? $course->duration : '0'}} Days</span></p>
                            </li>
                            @if($course->free == 1)
                                <li>
                                    <i class="fa fa-usd"></i>
                                    <p>Price<span>{{trans('labels.backend.courses.fields.free')}}</span></p>
                                </li>
                                <li>
                                    <i class="fa fa-usd"></i>
                                    <p>Price<span>{{trans('labels.backend.courses.fields.free')}}</span></p>
                                    <p class="subcontent">With Skype</p>
                                </li>
                            @else
                                <li>
                                    <i class="fa fa-usd"></i>
                                    <p>Price<span><font style="color: #e30613">{{$appCurrency['symbol']}}</font>{{$course->price}}</span></p>
                                </li>
                                <li>
                                    <i class="fa fa-usd"></i>
                                    <p>Price<span><font style="color: #e30613">{{$appCurrency['symbol']}}</font>{{$course->price_skype}}</span></p>
                                    <p class="subcontent">With Skype</p>
                                </li>
                            @endif
                        </ul>
                        @if (!$purchased_course)
                            @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))

                            @elseif(!auth()->check())
                                <a id="openLoginModal"
                                   class="btn btn-primary btn-block mb-15"
                                   data-target="#myModal" href="#">Enroll on this Course</a>
                                <a id="openLoginModal"
                                   class="btn btn-primary btn-block mb-15"
                                   data-target="#myModal" href="#">Gift this Course</a>
                            @elseif(auth()->check() && (auth()->user()->hasRole('student')))
                                <form action="{{ route('cart.singleCheckout') }}" method="POST" class="mb-2">
                                    @csrf
                                    <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                    <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                    <button class="btn btn-primary btn-block mb-15"
                                            href="#">Enroll on this Course</button>
                                </form>
                                <form action="{{ route('cart.singleCheckout') }}" method="POST" class="mb-2">
                                    @csrf
                                    <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                    <input type="hidden" name="gift_course" value="true"/>
                                    <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                    <button class="btn btn-primary btn-block mb-15"
                                            href="#">Gift this Course</button>
                                </form>
                            @else
                                <h6 class="alert alert-danger"> @lang('labels.frontend.course.buy_note')</h6>
                            @endif
{{--                        <a href="#" class="btn btn-primary btn-block mb-15">Enroll on this Course</a>--}}
                        @else
                            <form action="{{ route('cart.singleCheckout') }}" method="POST" class="mb-2">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                <input type="hidden" name="gift_course" value="true"/>
                                <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                <button class="btn btn-primary btn-block mb-15"
                                        href="#">Gift this Course</button>
                            </form>

{{--                            @if($continue_course)--}}
{{--                                <a href="{{route('lessons.show',['course_id' => $course->id,'slug'=>$continue_course->model->slug])}}"--}}
{{--                                   class="btn btn-primary btn-block mb-15">--}}

{{--                                    @lang('labels.frontend.course.continue_course')--}}

{{--                                    <i class="fa fa-arow-right"></i></a>--}}
{{--                            @endif--}}
                        @endif
                    </aside>
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
