@extends('frontend.layouts.app'.config('theme_layout'))

@push('after-styles')
    <link rel="stylesheet" href="https://cdn.plyr.io/3.5.3/plyr.css"/>
    <link href="{{asset('plugins/touchpdf-master/jquery.touchPDF.css')}}" rel="stylesheet">

    <style>
        .test-form {
            color: #333333;
        }

        .course-details-category ul li {
            width: 100%;
        }

        .sidebar.is_stuck {
            top: 15% !important;
        }

        .course-timeline-list {
            max-height: 300px;
            overflow: scroll;
        }

        .options-list li {
            list-style-type: none;
        }

        .options-list li.correct {
            color: green;

        }

        .options-list li.incorrect {
            color: red;

        }

        .options-list li.correct:before {
            content: "\f058"; /* FontAwesome Unicode */
            font-family: 'Font Awesome\ 5 Free';
            display: inline-block;
            color: green;
            margin-left: -1.3em; /* same as padding-left set on li */
            width: 1.3em; /* same as padding-left set on li */
        }

        .options-list li.incorrect:before {
            content: "\f057"; /* FontAwesome Unicode */
            font-family: 'Font Awesome\ 5 Free';
            display: inline-block;
            color: red;
            margin-left: -1.3em; /* same as padding-left set on li */
            width: 1.3em; /* same as padding-left set on li */
        }

        .options-list li:before {
            content: "\f111"; /* FontAwesome Unicode */
            font-family: 'Font Awesome\ 5 Free';
            display: inline-block;
            color: black;
            margin-left: -1.3em; /* same as padding-left set on li */
            width: 1.3em; /* same as padding-left set on li */
        }

        .touchPDF {
            border: 1px solid #e3e3e3;
        }

        .touchPDF > .pdf-outerdiv > .pdf-toolbar {
            height: 0;
            color: black;
            padding: 5px 0;
            text-align: right;
        }

        .pdf-tabs {
            width: 100% !important;
        }

        .pdf-outerdiv {
            width: 100% !important;
            left: 0 !important;
            padding: 0px !important;
            transform: scale(1) !important;
        }

        .pdf-viewer {
            left: 0px;
            width: 100% !important;
        }

        .pdf-drag {
            width: 100% !important;
        }

        .pdf-outerdiv {
            left: 0px !important;
        }

        .pdf-outerdiv {
            padding-left: 0px !important;
            left: 0px;
        }

        .pdf-toolbar {
            left: 0px !important;
            width: 99% !important;
            height: 30px;
        }

        .pdf-viewer {
            box-sizing: border-box;
            left: 0 !important;
            margin-top: 10px;
        }

        .pdf-title {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <!-- Start of breadcrumb section
            ============================================= -->
    <header>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <h1>{{$lesson->course->title}}</h1>
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
                    <p class="assign-content clearfix">
                        <span class="bold">{{$lesson->course->title}}</span>
                        <p> {!! $lesson->full_text !!} </p>
                    </p>
{{--                    <div class="row clearfix">--}}
{{--                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">--}}
{{--                            <img src="images/pic-full-1.jpg" class="img-full mb-15" alt="Images goes here" />--}}
{{--                        </div>--}}
{{--                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">--}}
{{--                            <img src="images/pic-full-2.jpg" class="img-full mb-15" alt="Images goes here" />--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <!-- COMMENTS -->
                    @if(count($lesson->comments) > 0)

                        @foreach($lesson->comments as $item)
                            <div class="discuss clearfix">
                                <div class="discuss-head clearfix">
                                    <img src="{{$item->user->picture}}" alt="" />
                                    <p>{{$item->user->full_name}}<span>{{$item->created_at->diffforhumans()}}</span></p>
                                </div>
                                <div class="discuss-box clearfix">
                                    <p class="discuss-txt clearfix">
                                    {!! nl2br($item->content) !!}

{{--                                        Hi David,<br /><br />--}}
{{--                                        MY name is Thomas Mangione and i have enrolled in your storytelling course. I tried uploadind the 10 images with my write-up but I am not sure it went through. The directions say create a title and then upload my images. It will only let me upload 1 image at a time? Everytime i upload a image i press preview and it says waiting for compelling image and then it lets me upload another image, Is that the way it is supposed to work? There is no save button. Attached is a screen shot of what I am experiencing.<br /><br />--}}
{{--                                        Thomas Mangione--}}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <h6> No Comments Yet.</h6>
                    @endif
{{--                    <div class="discuss clearfix">--}}
{{--                        <div class="discuss-head clearfix">--}}
{{--                            <img src="{{asset('assets_new/images/user.jpg')}}" alt="Images goes here" />--}}
{{--                            <p>Thomas Mangione<span>2 Months ago</span></p>--}}
{{--                        </div>--}}
{{--                        <div class="discuss-box clearfix">--}}
{{--                            <p class="discuss-txt clearfix">--}}
{{--                                Hi David,<br /><br />--}}
{{--                                MY name is Thomas Mangione and i have enrolled in your storytelling course. I tried uploadind the 10 images with my write-up but I am not sure it went through. The directions say create a title and then upload my images. It will only let me upload 1 image at a time? Everytime i upload a image i press preview and it says waiting for compelling image and then it lets me upload another image, Is that the way it is supposed to work? There is no save button. Attached is a screen shot of what I am experiencing.<br /><br />--}}
{{--                                Thomas Mangione--}}
{{--                            </p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="discuss clearfix">--}}
{{--                        <div class="discuss-head clearfix">--}}
{{--                            <img src="{{asset('assets_new/images/david-bathgate.jpg')}}" alt="Images goes here" />--}}
{{--                            <p>David Bathgate<span>2 Months ago</span></p>--}}
{{--                        </div>--}}
{{--                        <div class="discuss-box clearfix">--}}
{{--                            <p class="discuss-txt clearfix">--}}
{{--                                Hi Thomas Mangione,<br /><br />--}}
{{--                                First of all - thanks for enrolling on the course. Good to have you aboard. In answer to your question - yes, upload one image at a time (640 px. on the long side at 72 dpi). All this said, I am currently in Austria and do not always have an internet connection. Therefore, please go ahead and upload your work and I will get to it on the Sunday the 13th. My apologies for the delay. And I will have TCI grant you an additional week's tuition for this  inconvenience. Looking forward to working with you.<br /><br />--}}
{{--                                Best David--}}
{{--                            </p>--}}
{{--                        </div>--}}
{{--                    </div>--}}


                            <form class="mtb-30" action="{{route('lessons.comment',['id'=> $lesson->id])}}" method="POST" data-lead="Residential">
                                @csrf
                                <input type="hidden" name="rating" id="rating">

                                <textarea class="form-control custom-input mb-15  @if($errors->has('review')) border-bottom border-danger @endif" name="comment" id="textarea" rows="3" placeholder="Enter Text"></textarea>
                                @if($errors->has('comment'))
                                    <span class="help-block text-danger">{{ $errors->first('review', ':message') }}</span>
                                @endif

                                <button type="submit" name="submit" id="submit" class="btn btn-primary br-24 btn-padding btn-lg" value="Submit">CREATE</button>
                            </form>
                </div>

                <!-- SIDE -->
                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <div class="side-bg clearfix">
                        <div class="side-title clearfix">Course Instructor</div>
                        @foreach($lesson->course->teachers as $key=>$teacher)
                            @php $key++ @endphp
                            <div class="side-pic clearfix">
                                <img src="{{$teacher->picture}}" alt="" />
                                <p>{{$teacher->full_name}}</p>
                            </div>
                        @endforeach
                    </div>
                    @if($lesson->course->students->count() > 0)
                    <div class="side-bg clearfix">
                        <div class="side-title clearfix">Other Students from this Course</div>
                        <ul class="sidelinks clearfix">
                            @foreach($lesson->course->students as $key=>$student)
                                @if(Auth::user()->id != $student->id)
                                <li><a href="#">{{$student->full_name}}</a></li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="side-bg clearfix">
                        <div class="side-title clearfix">Assignments</div>
                        <ul class="sidelinks clearfix">
                            @if($lesson->assignments->count() > 0)
                                @foreach($lesson->assignments as $key=>$assignment)
                                    <li><a href="{{route('assignment.show',$assignment->id)}}">{{$assignment->title}}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                <!-- Photo -->

                    <div class="side-bg clearfix">
                        <div class="side-title clearfix">Photo's</div>
                        <div class="photos clearfix">
                            @if(count($photos) > 0)
                                @foreach($photos as $photo)
                            <a href="#" data-toggle="modal" data-target="#gridPhotos"><img src="{{$photo}}" alt="" /></a>
                                @endforeach
                            @endif
                        </div>
{{--                        <a href="#" class="btn btn-primary br-24 btn-padding">VIEW MORE</a>--}}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="gridPhotos" tabindex="-1" role="dialog" aria-labelledby="photos" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="photos clearfix">
                        @if(count($photos) > 0)
                            @foreach($photos as $photo)
                                <a id="gridPhotoImg" href="#" data-toggle="modal" data-target="#Photos"><img src="{{$photo}}" alt="" /></a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="Photos" tabindex="-1" role="dialog" aria-labelledby="photos" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <img id="big-photo" src="images/pic-full-1.jpg" class="img-full" alt="" />
                </div>
            </div>
        </div>
    </div>

    <!-- End of course details section
        ============================================= -->
@endsection

@push('after-scripts')
    <script src="{{asset('plugins/sticky-kit/sticky-kit.js')}}"></script>
    <script src="https://cdn.plyr.io/3.5.3/plyr.polyfilled.js"></script>
    <script src="{{asset('plugins/touchpdf-master/pdf.compatibility.js')}}"></script>
    <script src="{{asset('plugins/touchpdf-master/pdf.js')}}"></script>
    <script src="{{asset('plugins/touchpdf-master/jquery.touchSwipe.js')}}"></script>
    <script src="{{asset('plugins/touchpdf-master/jquery.touchPDF.js')}}"></script>
    <script src="{{asset('plugins/touchpdf-master/jquery.panzoom.js')}}"></script>
    <script src="{{asset('plugins/touchpdf-master/jquery.mousewheel.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script>

        $(document).ready(function () {
            $(document).on('click', '#gridPhotoImg', function () {
                var imgSrc = $(this).children('img')[0].src;

                if(imgSrc){
                    $('#big-photo').attr('src',imgSrc);
                    $('Photos').modal('show');
                }
            });
        });


        @if($lesson->mediaPDF)
            $(function () {
                $("#myPDF").pdf({
                    source: "{{asset('storage/uploads/'.$lesson->mediaPDF->name)}}",
                    loadingHeight: 800,
                    loadingWidth: 800,
                    loadingHTML: ""
                });

            });
        @endif

        var storedDuration = 0;
        var storedLesson;
        storedDuration = Cookies.get("duration_" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}");
        storedLesson = Cookies.get("lesson" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}");
        var user_lesson;

        if (parseInt(storedLesson) != parseInt("{{$lesson->id}}")) {
            Cookies.set('lesson', parseInt('{{$lesson->id}}'));
        }


                @if($lesson->mediaVideo && $lesson->mediaVideo->type != 'embed')
            var current_progress = 0;


        @if($lesson->mediaVideo->getProgress(auth()->user()->id) != "")
            current_progress = "{{$lesson->mediaVideo->getProgress(auth()->user()->id)->progress}}";
                @endif



        const player2 = new Plyr('#audioPlayer');

        const player = new Plyr('#player');
        duration = 10;
        var progress = 0;
        var video_id = $('#player').parents('.video-container').data('id');
        player.on('ready', event => {
            player.currentTime = parseInt(current_progress);
        duration = event.detail.plyr.duration;


        if (!storedDuration || (parseInt(storedDuration) === 0)) {
            Cookies.set("duration_" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}", duration);
        }

        });

        {{--if (!storedDuration || (parseInt(storedDuration) === 0)) {--}}
            {{--Cookies.set("duration_" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}", player.duration);--}}
            {{--}--}}


        setInterval(function () {
            player.on('timeupdate', event => {
                if ((parseInt(current_progress) > 0) && (parseInt(current_progress) < parseInt(event.detail.plyr.currentTime))) {
                progress = current_progress;
            } else {
                progress = parseInt(event.detail.plyr.currentTime);
            }
        });
            if(duration !== 0 || parseInt(progress) !== 0 ) {
                saveProgress(video_id, duration, parseInt(progress));
            }
        }, 3000);


        function saveProgress(id, duration, progress) {
            $.ajax({
                url: "{{route('update.videos.progress')}}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'video': parseInt(id),
                    'duration': parseInt(duration),
                    'progress': parseInt(progress)
                },
                success: function (result) {
                    if (progress === duration) {
                        location.reload();
                    }
                }
            });
        }


        $('#notice').on('hidden.bs.modal', function () {
            location.reload();
        });

        @endif

        $("#sidebar").stick_in_parent();


        @if((int)config('lesson_timer') != 0)
        //Next Button enables/disable according to time

        var readTime, totalQuestions, testTime;
        user_lesson = Cookies.get("user_lesson_" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}");

        @if ($test_exists )
            totalQuestions = '{{count($lesson->questions)}}'
        readTime = parseInt(totalQuestions) * 30;
        @else
        readTime = parseInt("{{$lesson->readTime()}}") * 60;
        @endif

        @if(!$lesson->isCompleted())
            storedDuration = Cookies.get("duration_" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}");

        storedLesson = Cookies.get("lesson" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}");

        if(storedDuration > 0){
            var totalLessonTime = parseInt(storedDuration) ? parseInt(storedDuration) : 0;
        }
        else {
            var totalLessonTime = readTime + (parseInt(storedDuration) ? parseInt(storedDuration) : 0);
        }


        var storedCounter = (Cookies.get("storedCounter_" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}")) ? Cookies.get("storedCounter_" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}") : 0;
        var counter;
        if (user_lesson) {
            if (user_lesson === 'true') {
                counter = 1;
            }
        } else {
            if ((storedCounter != 0) && storedCounter < totalLessonTime) {
                counter = storedCounter;
            } else {
                counter = totalLessonTime;
            }
        }
        var interval = setInterval(function () {
            counter--;
            // Display 'counter' wherever you want to display it.
            if (counter >= 0) {
                // Display a next button box
                $('#nextButton').html("<a class='btn btn-block bg-danger font-weight-bold text-white' href='#'>@lang('labels.frontend.course.next') (in " + counter + " seconds)</a>")
                Cookies.set("duration_" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}", counter);

            }
            if (counter === 0) {
                Cookies.set("user_lesson_" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}", 'true');
                Cookies.remove('duration');

                @if ($test_exists && (is_null($test_result)))
                    $('#nextButton').html("<a class='btn btn-block bg-danger font-weight-bold text-white' href='#'>@lang('labels.frontend.course.complete_test')</a>")
                @else
            @if($next_lesson)
                    $('#nextButton').html("<a class='btn btn-block gradient-bg font-weight-bold text-white'" +
                        " href='{{ route('lessons.show', [$next_lesson->course_id, $next_lesson->model->slug]) }}'>@lang('labels.frontend.course.next')<i class='fa fa-angle-double-right'></i> </a>");
                @else
                $('#nextButton').html("<form method='post' action='{{route("admin.certificates.generate")}}'>" +
                "<input type='hidden' name='_token' id='csrf-token' value='{{ Session::token() }}' />" +
                "<input type='hidden' value='{{$lesson->course->id}}' name='course_id'> " +
                "<button class='btn btn-success btn-block text-white mb-3 text-uppercase font-weight-bold' id='finish'>@lang('labels.frontend.course.finish_course')</button></form>");

                @endif

                @if(!$lesson->isCompleted())
                    courseCompleted("{{$lesson->id}}", "{{get_class($lesson)}}");
                @endif
                @endif
                clearInterval(counter);
            }
        }, 1000);

        @endif
        @endif

        function courseCompleted(id, type) {
            $.ajax({
                url: "{{route('update.course.progress')}}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'model_id': parseInt(id),
                    'model_type': type,
                },
            });
        }
    </script>

@endpush