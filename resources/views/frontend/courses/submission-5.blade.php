@extends('frontend.layouts.app'.config('theme_layout'))

@push('after-styles')
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
                        <span class="bold">Your Photo or Video Assignment Submission</span>
                        Next you must :
                    </p>
                    <ul class="sidelinks clearfix">
                        <li>Attach your photos or videos : Upload the first, adding additional ones using the “Add an attachment to your submission” button below. Photo should not exceed 500 px on the long side and Video should not exceed 150 mb in size.</li>
                    </ul>
                    <p class="assign-content clearfix">You may also :</p>
                    <ul class="sidelinks clearfix">
                        <li>Edit your Submission</li>
                        <li>Sequence your Photo's</li>
                    </ul>
                    <p class="assign-content clearfix">
                        Now - you must SUBMIT your assignment for instructor critique and comments. VERY IMPORTANT: If you do not "submit" your assignment, your instructor will not receive notification that you have completed it.<br /><br />
                        * Note that you may always return to submit additional photos or videos, as well as edit and sequence you assignment work.
                    </p>
                    <!-- <div class="btn-group btn-group-sm mb-15" role="group" aria-label="Basic example">
                      <button type="button" class="btn btn-outline-secondary">Add an attachment to your Submission</button>
                      <button type="button" class="btn btn-outline-secondary">Sequence your Images</button>
                      <button type="button" class="btn btn-outline-secondary">Edit Assignment Submission</button>
                    </div> -->
                    <div class="border-box clearfix">
                        <ul class="vertical-list clearfix">
                            <li>
                                <p class="assign-content clearfix">
                    <span>
                      Project Statement<br />
                      Your description<br />
                      Hi David,<br /><br />
                      I submitted some ideas onto one of the earlier forum pages and will just taker it from here. I am working on a family based project, essentially an homage to my parents, and want to document some of the work they undertake in the summer season, which is the harvesting, apckaging and sale of their crop of flowers. This takes place during a brief window of opportunity, and tends to have run its course by mid-July. They tend to undetake much of the work themselves although occassionally they get help in, and sometimes myself or some of the rest of our family are on board to give a hand. Although I have not as yet planned the  type of images that could make this project work or extend its interest to outside viewers I do have some rekkie pictures taken.<br /><br />Details that may be included or that may be of possible interest to an onlooker would be the cutting of the flowers in the field, collection and bringing them into the yard for offloading, preparation and bunching of the flowers (packaging), loading up, and possibly some of the deliveries.<br /><br />
                      I'm going to upload ten images - if you think this may not be feasible for my current project work let me know and I will get into gear eith some of my other suggestions.<br /><br />
                      With thanks,<br />
                      Ingrid.<br /><br />
                      PS I am also behind schedule on the lessons but am focused on getting through them - my apologies.
                    </span>
                                </p>
                                <p class="assign-content clearfix">
                                    Critique Summary by David Bathgate<br /><br />
                                    Ingrid,  Having now seen a bit of what you earlier were describing as a story possibility, I'd have to say that this situation may well be a good one.  The main thing is that it is "active" and you are already an "insider." to it.  What you do need to do, however, is to follow the production and handling process as much as possible and follow the story clear through to sales to the consumer.  And keep in mind that the strongest photos don't simply "document" what is happening, they use differing perspectives and camera controls to "interpret" what you as the photographer see and feel to be taking place.  You want action, you want "moments" of peak occurrence, expressions, human interaction, etc.<br /><br />
                                    Let me know if you have questions about this and / or if you would like to discuss your potential story choices, further.<br /><br />
                                    David
                                </p>
                                <a href="critiques.html" class="btn btn-primary btn-padding btn-sm mb-15">Respond to this Critique</a>
                            </li>
                            <li>
                                <p class="assign-content clearfix">
                    <span>
                      Project Statement<br />
                      Your description<br />
                      Hi David,<br /><br />
                      I submitted some ideas onto one of the earlier forum pages and will just taker it from here. I am working on a family based project, essentially an homage to my parents, and want to document some of the work they undertake in the summer season, which is the harvesting, apckaging and sale of their crop of flowers. This takes place during a brief window of opportunity, and tends to have run its course by mid-July. They tend to undetake much of the work themselves although occassionally they get help in, and sometimes myself or some of the rest of our family are on board to give a hand. Although I have not as yet planned the  type of images that could make this project work or extend its interest to outside viewers I do have some rekkie pictures taken.<br /><br />Details that may be included or that may be of possible interest to an onlooker would be the cutting of the flowers in the field, collection and bringing them into the yard for offloading, preparation and bunching of the flowers (packaging), loading up, and possibly some of the deliveries.<br /><br />
                      I'm going to upload ten images - if you think this may not be feasible for my current project work let me know and I will get into gear eith some of my other suggestions.<br /><br />
                      With thanks,<br />
                      Ingrid.<br /><br />
                      PS I am also behind schedule on the lessons but am focused on getting through them - my apologies.
                    </span>
                                </p>
                                <p class="assign-content clearfix">
                                    Critique Summary by David Bathgate<br /><br />
                                    Ingrid,  Having now seen a bit of what you earlier were describing as a story possibility, I'd have to say that this situation may well be a good one.  The main thing is that it is "active" and you are already an "insider." to it.  What you do need to do, however, is to follow the production and handling process as much as possible and follow the story clear through to sales to the consumer.  And keep in mind that the strongest photos don't simply "document" what is happening, they use differing perspectives and camera controls to "interpret" what you as the photographer see and feel to be taking place.  You want action, you want "moments" of peak occurrence, expressions, human interaction, etc.<br /><br />
                                    Let me know if you have questions about this and / or if you would like to discuss your potential story choices, further.<br /><br />
                                    David
                                </p>
                                <a href="critiques.html" class="btn btn-primary btn-padding btn-sm mb-15">Respond to this Critique</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <a href="attachment.html" class="btn btn-primary br-24 btn-paddiing btn-block mb-15">Add an attachment</a>
                    <a href="photo-sequence.html" class="btn btn-primary br-24 btn-paddiing btn-block mb-15">Sequence your Images</a>
                    <a href="edit-assignment.html" class="btn btn-primary br-24 btn-paddiing btn-block mb-15">Edit Assignment Submission</a>
                    <div class="side-bg clearfix">
                        <div class="side-title clearfix">Course Instructor</div>
                        @foreach($course->teachers as $key=>$teacher)
                            @php $key++ @endphp
                            <div class="side-pic clearfix">
                                <img src="{{$teacher->picture}}" alt="" />
                                <p>{{$teacher->full_name}}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="side-bg clearfix">
                        <div class="side-title clearfix">Submit your Assignment</div>
                        <p class="assign-txt clearfix"><a href="{{route('submission.create', $assignment->id)}}">Create your first submission</a></p>
                        <p class="assign-txt clearfix">You have submitted your assignment to your instructor to critique.</p>
                    </div>
                    <div class="side-bg clearfix">
                        <div class="side-title clearfix">Photo's</div>
                        <div class="photos clearfix">
                            <a href="#" data-toggle="modal" data-target="#gridPhotos"><img src="images/pic-1.jpg" alt="Images goes here" /></a>
                            <a href="#" data-toggle="modal" data-target="#gridPhotos"><img src="images/pic-2.jpg" alt="Images goes here" /></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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