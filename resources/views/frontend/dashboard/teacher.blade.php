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
                            <a class="nav-link" id="v-pills-courses-past-and-future" data-toggle="pill" href="#courses-past-and-future" role="tab" aria-controls="v-pills-courses-past-and-future" aria-selected="false">Courses Past & Future</a>
                            <a class="nav-link" id="v-pills-recent-updates" data-toggle="pill" href="#recent-updates" role="tab" aria-controls="v-pills-recent-updates" aria-selected="false">Recent Updates</a>
                            <a class="nav-link" id="v-pills-my-students" data-toggle="pill" href="#my-students" role="tab" aria-controls="v-pills-my-students" aria-selected="false">My Students</a>
                            <a class="nav-link" id="v-pills-my-account" data-toggle="pill" href="#my-account" role="tab" aria-controls="v-pills-my-account" aria-selected="false">My Account</a>
                            <a class="nav-link" id="v-pills-my-password" data-toggle="pill" href="#my-password" role="tab" aria-controls="v-pills-my-password" aria-selected="false">Password</a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                    @include('includes.partials.messages')

                    <div class="tab-content" id="v-pills-tabContent">
                        <div class="tab-pane fade show active" id="dashboard" role="tabpanel" aria-labelledby="v-pills-dashboard">
                        <div id="running-courses">
                            <p class="head clearfix">COURSES CURRENTLY RUNNING</p>
                            <ul class="nav justify-content-end">
                                <li class="nav-item"><a class="nav-link active" href="{{route('admin.teacher.dashboard')}}">Running with Enrollments</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{route('admin.teacher.dashboard', ['all' => 1])}}">All Running</a></li>
                            </ul>
                            <table cellspacing="0" cellpadding="0" class="table table-condensed table-bordered table-hover cf">
                                <thead>
                                <tr>
                                    <th class="centred">Course</th>
                                    <th class="centred">Enrollments</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $fil_all = request()->input('all')? true: false; @endphp
                                @if(count($all_courses) > 0)
                                    @foreach($all_courses as $item)
                                        @if((!$fil_all && $item->students()->count() > 0) || $fil_all)
                                        <tr>
                                            <td class="centred"><a href="{{route('courses.teacher.show',$item->id)}}">{{$item->title}}</a></td>
                                            <td class="centred"><a href="{{route('courses.teacher.show',['id'=>$item->id, 'enrolment'=>'1'])}}">{{$item->students()->count()}}</a></td>
                                        </tr>
                                        @endif
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="2" class="centred">No Data Available.</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        </div>

                        <div class="tab-pane fade" id="courses-past-and-future" role="tabpanel" aria-labelledby="v-pills-courses-past-and-future">
                            <div id="all-courses">
                                <p class="head clearfix">Scheduled Courses</p>
                                <ul class="nav justify-content-end">
                                    <li class="nav-item"><a class="nav-link active" href="{{route('admin.teacher.dashboard')}}">With Enrollments</a></li>
                                    <li class="nav-item"><a class="nav-link" href="{{route('admin.teacher.dashboard', ['past_all' => 1])}}">All</a></li>
                                </ul>
                                <table cellspacing="0" cellpadding="0" class="table table-condensed table-bordered table-hover cf">
                                    <thead>
                                    <tr>
                                        <th class="centred">Course</th>
                                        <th class="centred">Enrollments</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $fil_past = request()->input('past_all')? true: false; @endphp
                                    @if(count($all_with_trashed) > 0)
                                        @foreach($all_with_trashed as $item)
                                            @if((!$fil_past && $item->students()->count() > 0) || $fil_past)
                                                <tr>
                                                    <td class="centred"><a href="{{route('courses.teacher.show',$item->id)}}">{{$item->title}}</a></td>
                                                    <td class="centred"><a href="{{route('courses.teacher.show',['id'=>$item->id, 'enrolment'=>'1'])}}">{{$item->students()->count()}}</a></td>
                                                    @if($item->trashed())
                                                        <td><div class="bullet-red"></div></td>
                                                    @else
                                                        <td><div class="bullet-green"></div></td>
                                                    @endif
                                                </tr>
                                            @endif

                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="centred">No Data Available.</td>
                                        </tr>
                                    @endif
{{--                                    <tr>--}}
{{--                                        <td class="centred"><a href="#">One-Month Mentorship - Prasenjit Sengupta</a></td>--}}
{{--                                        <td class="centred"><a href="#">1</a></td>--}}
{{--                                        <td><div class="bullet-green"></div></td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td class="centred"><a href="#">Photos from Home</a></td>--}}
{{--                                        <td class="centred"><a href="#">0</a></td>--}}
{{--                                        <td><div class="bullet-green"></div></td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td class="centred"><a href="#">Telling the Story in Pictures</a></td>--}}
{{--                                        <td class="centred"><a href="#">2</a></td>--}}
{{--                                        <td><div class="bullet-green"></div></td>--}}
{{--                                    </tr>--}}
{{--                                    <tr>--}}
{{--                                        <td class="centred"><a href="#">Environmental Portraiture</a></td>--}}
{{--                                        <td class="centred"><a href="#">0</a></td>--}}
{{--                                        <td><div class="bullet-green"></div></td>--}}
{{--                                    </tr>--}}
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="recent-updates" role="tabpanel" aria-labelledby="v-pills-recent-updates">
                            <p class="head clearfix">Recent Updates</p>
                            @if(count($logs) > 0)
                            <p class="assign-content clearfix"><span>Assignments<a href="{{route('log.clear', ['teacher_id'=>auth()->user()->id])}}" class="btn btn-primary float-right"><i class="fa fa-times-circle"></i> Clear all</a></span></p>
                            @endif
                            <table cellspacing="0" cellpadding="0" class="table table-condensed table-bordered cf">
                                <tbody>
                                @if(count($logs) > 0)
                                    @foreach($logs as $log)
                                        <tr>
                                            <td>{{\Carbon\Carbon::parse($log->created_at)->format('d M Y | g:i A')}}</td>
                                            @if($log->submission_id != null)
                                                <td><a href="{{route('student.submission.show', ['assignment_id'=>$log->submission->assignment->id, 'submission_id'=>$log->submission->id])}}">{{ $log->title }}</a></td>
                                            @else
                                                <td>{{ $log->title }}</td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                        <tr>
                                            <td colspan="2" class="centred">No Data Available.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="tab-pane fade" id="my-students" role="tabpanel" aria-labelledby="v-pills-my-students">
                            <div id="my-students">
                                <p class="head clearfix">My Students</p>
                                <p class="assign-txt clearfix">Enrollments<span class="float-right">Total : 1</span></p>
                                <table cellspacing="0" cellpadding="0" class="table table-condensed table-bordered table-hover cf">
                                    <thead>
                                    <tr>
                                        <th class="centred">Student</th>
                                        <th class="centred">Email</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($enrollStudent) > 0)
                                        @foreach($enrollStudent as $item)
                                                <tr>
                                                    <td class="centred"><a href="{{route('courses.student.detail', ['id'=>$item['id']])}}">{{$item['full_name']}}</a></td>
                                                    <td class="centred">{{$item['email']}}</td>
                                                    <td><div class="bullet-green"></div></td>
                                                </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="centred">No Data Available.</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
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
    <form id="genPdfForm" name="genPdfForm" target="_blank" method="post" action="{{route('generate.pdf')}}" role="form">
        @csrf
        <input type="hidden" id="pdf_lesson_id" name="pdf_lesson_id" value=""/>
        <input type="hidden" id="pdf_assignment_id" name="pdf_assignment_id" value=""/>
    </form>

@endsection

@push('after-scripts')
    <script>

        function generatePdf(id){
            var thisform = document.forms.genPdfForm;
            thisform.pdf_lesson_id.value = id;
            thisform.pdf_assignment_id.value = '';
            thisform.submit();
        }

        function generateAssignmentPdf(id){
            var thisform = document.forms.genPdfForm;
            thisform.pdf_lesson_id.value = '';
            thisform.pdf_assignment_id.value = id;
            thisform.submit();
        }

        $(document).ready(function () {
        });

    </script>
@endpush