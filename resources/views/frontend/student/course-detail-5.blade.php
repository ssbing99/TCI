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
                    <h1>{{$student->full_name}}</h1>
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
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <p class="head clearfix">Courses</p>
                    <table cellspacing="0" cellpadding="0" class="table table-condensed table-bordered cf">
                        <thead>
                        <tr>
                            <th class="centred">Course</th>
                            <th class="centred">End on</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($courses->count() > 0)
                            @foreach($courses as $course)
                        <tr>
                            <td class="centred"><a href="{{route('courses.teacher.show',$course->id)}}">{{$course->title}}</a></td>
                            <td class="centred">30 November 2020 @ 19:37</td>
                        </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <p class="head clearfix">Lessons</p>

                    @if($courses->count() > 0)
                        @foreach($courses as $course)
                            <table cellspacing="0" cellpadding="0" class="table table-bordered table-condensed cf">
                                <thead>
                                    <tr>
                                        <th class="centred">{{$course->title}}</th>
                                        <th width="10%"><i class="fa fa-comments"></i></th>
                                        <th width="10%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($course->lessons as $aLesson)
                                    <tr>
                                        <td class="centred"><a href="{{route('lessons.show',['course_id' => $course->id,'slug'=>$aLesson->slug])}}">{{$aLesson->title}}</a></td>
                                        <td><a href="{{route('lessons.show',['course_id' => $course->id,'slug'=>$aLesson->slug])}}">{{$aLesson->comments()->count()}}</a></td>
                                        @if($aLesson->trashed())
                                            <td><div class="bullet-red"></div></td>
                                        @else
                                            <td><div class="bullet-green"></div></td>
                                        @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    @endif

                    <p class="head clearfix">Assignments</p>
                    @if($courses->count() > 0)
                        @foreach($courses as $course)
                            <table cellspacing="0" cellpadding="0" class="table table-bordered table-condensed cf">
                                <thead>
                                <tr>
                                    <th class="centred">{{$course->title}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($course->lessons as $aLesson)
                                    @foreach($aLesson->assignments as $aAssignment)
                                        @foreach($aAssignment->submissions as $aSubmission)
                                            <tr>
                                                <td class="centred"><a href="assignment-submissions.html">{{$aSubmission->title}}</a></td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    @endif
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