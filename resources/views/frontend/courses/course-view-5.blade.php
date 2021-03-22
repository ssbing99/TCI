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
                    <h1>Scheduled Courses</h1>
                </div>
            </div>
        </div>
    </header>
    <!-- End of breadcrumb section
        ============================================= -->

    <!-- Start of course details section
        ============================================= -->
    @php $isEnrol = request()->input('enrolment')? true: false; @endphp
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <p class="head clearfix">People Photography - with Confidence!</p>
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{$isEnrol? '':'active'}}" id="pills-lesson-plan" data-toggle="pill" href="#lesson-plan" role="tab" aria-controls="pills-lesson-plan" aria-selected="{{$isEnrol? 'false':'true'}}">Lesson Plan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{$isEnrol? 'active':''}}" id="pills-enrollments" data-toggle="pill" href="#enrollments" role="tab" aria-controls="pills-enrollments" aria-selected="{{$isEnrol? 'true':'false'}}">Enrollments</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade {{$isEnrol? '':'show active'}}" id="lesson-plan" role="tabpanel" aria-labelledby="pills-lesson-plan">
                            <p class="head clearfix">Lesson Plan</p>
                            <table cellspacing="0" cellpadding="0" class="table table-bordered table-condensed table-hover cf">
                                <thead>
                                <tr>
                                    <th colspan="2">Lesson/Assignment</th>
                                    <th><i class="fa fa-comments"></i></th>
                                    <th><i class="fa fa-upload"></i></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($course->lessons as $lesson)
                                    <tr>
                                        <td><span class="badge badge-secondary">Lesson</span></td>
                                        <td><a href="{{route('lessons.show',['course_id' => $course->id,'slug'=>$lesson->slug])}}">{{$lesson->title}}</a></td>
                                        <td><a href="{{route('lessons.show',['course_id' => $course->id,'slug'=>$lesson->slug])}}">{{$lesson->comments()->count()}}</a></td>
                                        <td></td>
                                        @if($lesson->trashed())
                                            <td><div class="bullet-red"></div></td>
                                        @else
                                            <td><div class="bullet-green"></div></td>
                                        @endif
                                    </tr>
                                    @foreach($lesson->assignments as $assignment)
                                        <tr>
                                            <td><span class="badge badge-primary">Assignment</span></td>
                                            <td><a href="{{route('student.assignment.show',$assignment->id)}}">{{$assignment->title}}</a></td>
                                            <td><a href="{{route('student.assignment.show',$assignment->id)}}">{{$assignment->comments()->count()}}</a></td>
                                            <td><a href="{{route('student.assignment.show',$assignment->id)}}">{{$assignment->submissions()->count()}}</a></td>
                                            @if($assignment->trashed())
                                                <td><div class="bullet-red"></div></td>
                                            @else
                                                <td><div class="bullet-green"></div></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade {{$isEnrol? 'show active':''}}" id="enrollments" role="tabpanel" aria-labelledby="pills-enrollments">
                            <p class="head clearfix">Enrollments</p>
                            <table cellspacing="0" cellpadding="0" class="table table-condensed table-bordered cf">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($course->students as $key=>$student)
                                <tr>
                                    <td>{{$student->full_name}}</td>
                                    <td><a rel="Copy this email address to the clipboard, open a new browser<br />window and go to the login page. Paste the email address and use<br />the password <strong>blah</strong> to see the user's eye view of the site" href="#">{{$student->email}}</a></td>
                                </tr>
                                @endforeach
{{--                                <tr>--}}
{{--                                    <td>Sumit Mahto</td>--}}
{{--                                    <td><a rel="Copy this email address to the clipboard, open a new browser<br />window and go to the login page. Paste the email address and use<br />the password <strong>blah</strong> to see the user's eye view of the site" href="#">sumitmca11@gmail.com</a></td>--}}
{{--                                </tr>--}}
                                </tbody>
                            </table>
                        </div>
                    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>

    <script>

        function openLoginWithSession(isGift){
            //clean
            $('#workshopId').val('');
            $('#workshopType').val('');

            $('#enrollId').val('{{$course->id}}');
            $('#isGift').val(isGift);
            Cookies.set('withEnroll', '{"courseId": "{{$course->id}}", "giftCourse": "'+isGift+'"}');
            $('#openLoginModal').click();
        }

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
