@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )

@push('after-styles')
    <style>
     .listing-filter-form select{
            height:50px!important;
        }

        .page-item.active .page-link {
            color: #a1ca00;
            background-color: inherit;
            border-color: inherit;
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
                    <h1>@lang('navs.frontend.dashboard')</h1>
                </div>
            </div>
        </div>
    </header>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of course section
        ============================================= -->
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <div class="dashboard-bg clearfix">
                        <div class="profile-img clearfix">
                            <img src="{{auth()->user()->picture}}" alt="" class="profile-img" />
                        </div>
                        <div class="profile-user clearfix">{{auth()->user()->full_name}}</div>
{{--                        <center><a class="btn btn-primary btn-md br-24 mtb-15" data-toggle="pill" href="#my-account" role="tab" aria-controls="v-pills-my-account"  aria-selected="false">Edit Profile</a></center>--}}
                        <hr />
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="v-pills-dashboard" data-toggle="pill" href="#dashboard" role="tab" aria-controls="v-pills-dashboard" aria-selected="true">Dashboard</a>
                            <a class="nav-link" id="v-pills-my-account" data-toggle="pill" href="#my-account" role="tab" aria-controls="v-pills-my-account" aria-selected="false">My Account</a>
                            <a class="nav-link" id="v-pills-my-password" data-toggle="pill" href="#my-password" role="tab" aria-controls="v-pills-my-password" aria-selected="false">Password</a>
                            <a class="nav-link" id="v-pills-courses" data-toggle="pill" href="#courses" role="tab" aria-controls="v-pills-courses" aria-selected="false">Courses</a>
                            <a class="nav-link" id="v-pills-assignment" data-toggle="pill" href="#assignment" role="tab" aria-controls="v-pills-assignment" aria-selected="false">Assignment</a>
{{--                            <a class="nav-link" id="v-pills-help" data-toggle="pill" href="#help" role="tab" aria-controls="v-pills-help" aria-selected="false">Help</a>--}}
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                    @include('includes.partials.messages')

                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="v-pills-dashboard">
                            <p class="content clearfix">Online-Interactive Courses in Photography and Multimedia Storytelling - Taught by the Professionals</p>
                            <p class="head clearfix">My Courses</p>

                            @if(count($purchased_courses) > 0)
                                <table cellspacing="0" cellpadding="0" class="table table-condensed table-striped table-bordered cf">
                                    <thead>
                                    <tr>
                                        <th>Course Name</th>
                                        <th>Duration</th>
                                        <th>End Date</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($purchased_courses as $item)

                                        <tr>
                                            <td>{{$item->title}}</td>
                                            <td>{{$item->duration}} Days</td>
                                            <td>30<sup>th</sup> Aug 2015</td>
                                            <td><a href="{{route('courses.review.show',['slug'=>$item->slug])}}" class="btn btn-primary br-24">Add Review</a>
                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="col-12 text-center">
                                    <h4 class="text-center">@lang('labels.backend.dashboard.no_data')</h4>
                                    <a class="btn btn-primary"
                                       href="{{route('courses.all')}}">@lang('labels.backend.dashboard.buy_course_now')
                                        <i class="fa fa-arrow-right"></i></a>
                                </div>
                            @endif

                            @if(count($purchased_courses) > 0)
                            <p class="head clearfix">My Assignments</p>
                                <ul class="profile-list clearfix">
                                @foreach($purchased_courses as $item)
                                    @foreach($item->publishedLessons as $key=> $lesson)
                                        @foreach($lesson->assignments as $akey=> $assignment)
                                                <li><i class="fa fa-file-text"></i> <a href="{{route('assignment.show',$assignment->id)}}">{{$assignment->title}}</a></li>
                                        @endforeach
                                    @endforeach
                                @endforeach
                            </ul>

                            @endif
                        </div>
                        <div class="tab-pane fade" id="my-account" role="tabpanel" aria-labelledby="v-pills-my-account">
                            <p class="content clearfix">Online-Interactive Courses in Photography and Multimedia Storytelling - Taught by the Professionals</p>
                            <p class="head clearfix">Login Information<span>Leave the password and confirmation fields blank if you do not want to change your password.</span></p>
                            @include('frontend.layouts.partials.account-edit')
                        </div>
                        <div class="tab-pane fade" id="my-password" role="tabpanel" aria-labelledby="v-pills-my-password">
                            <p class="content clearfix">Online-Interactive Courses in Photography and Multimedia Storytelling - Taught by the Professionals</p>
                            <p class="head clearfix">Login Information<span>Leave the password and confirmation fields blank if you do not want to change your password.</span></p>
                            @include('frontend.layouts.partials.account-password')
                        </div>
                        <div class="tab-pane fade" id="courses" role="tabpanel" aria-labelledby="v-pills-courses">
                            <p class="content clearfix">Online-Interactive Courses in Photography and Multimedia Storytelling - Taught by the Professionals</p>
                            @if(count($purchased_courses) > 0)
                                @foreach($purchased_courses as $item)
                                    <p class="head clearfix">{{$item->title}}</p>
                                    <ul class="profile-list clearfix">
                                    @foreach($item->publishedLessons as $key=> $lesson)
                                            <li><i class="fa fa-file-pdf-o"></i> <a href="{{route('lessons.show',['course_id' => $item->id,'slug'=>$lesson->slug])}}">{{$lesson->title}}</a><span><a href="#" class="btn btn-primary btn-padding br-24">Download PDF</a></span></li>
                                    @endforeach
                                    </ul>
                                @endforeach
                            @else
                                <p>@lang('labels.general.no_data_available')</p>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="assignment" role="tabpanel" aria-labelledby="v-pills-assignment">
                            <p class="content clearfix">Online-Interactive Courses in Photography and Multimedia Storytelling - Taught by the Professionals</p>
                            <p class="head clearfix">My Assignments</p>
                            @if(count($purchased_courses) > 0)
                                <ul class="assignment-list clearfix">
                                    @foreach($purchased_courses as $item)
                                        @foreach($item->publishedLessons as $key=> $lesson)
                                            @foreach($lesson->assignments as $akey=> $assignment)
                                                <li>
                                                    <p>
                                                        <a href="{{route('assignment.show',$assignment->id)}}">{{$assignment->title}}</a>
                                                        @if($assignment->submissionsById(auth()->user()->id)->count() > 0)
                                                            <span>View your submission : <a href="submissions.html">{{$assignment->submissionsById(auth()->user()->id)->first()->title}}</a> (submitted for critique)</span>
                                                        @else
                                                            <span>Not yet submitted</span>
                                                        @endif
                                                    </p>
                                                    <span class="float-right"><a href="#" class="btn btn-primary btn-padding br-24">Generate Pdf</a></span>
                                                </li>
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </ul>
                            @else
                                <p>@lang('labels.general.no_data_available')</p>
                            @endif
                        </div>
{{--                        <div class="tab-pane fade" id="help" role="tabpanel" aria-labelledby="v-pills-help">--}}
{{--                            <p class="content clearfix">Online-Interactive Courses in Photography and Multimedia Storytelling - Taught by the Professionals</p>--}}
{{--                            <p class="head clearfix">Help(Contact Form)</p>--}}
{{--                            <form method="post" action="" role="form">--}}
{{--                                <div class="row clearfix">--}}
{{--                                    <div class="col-12 form-group">--}}
{{--                                        <input type="text" name="helpSubject" id="helpSubject" class="form-control custom-input" placeholder="Subject" />--}}
{{--                                    </div>--}}
{{--                                    <div class="col-12 form-group">--}}
{{--                                        <textarea class="form-control custom-input" name="helpMessage" id="helpMessage" placeholder="Message" rows="3"></textarea>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-12">--}}
{{--                                        <input type="submit" name="submit" id="submit" class="btn btn-primary btn-padding br-24 btn-md" value="Submit" />--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </form>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End of course section
        ============================================= -->

    <!-- Start of best course
   =============================================  -->
{{--    @include('frontend.layouts.partials.browse_courses2')--}}
    <!-- End of best course
            ============================================= -->


@endsection

@push('after-scripts')
    <script>
        $(document).ready(function () {
        });

    </script>
@endpush