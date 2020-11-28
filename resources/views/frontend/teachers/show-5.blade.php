@extends('frontend.layouts.app'.config('theme_layout'))
@push('after-styles')
    <style>
        .section-title-2 h2:after {
            background: #ffffff;
            bottom: 0px;
            position: relative;
        }

        .course-meta li {
            font-family: "Roboto-Regular", Arial;
            font-size: 14px;
            color: #777;
            text-decoration: none;
            text-align: center;
            line-height: 20px;
        }
    </style>
@endpush
@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    <div class="banner custom-banner-bg">
        <div class="container">
            <div class="page-heading text-sm-center">
                Teacher User
            </div>
        </div>
    </div>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of teacher details area
        ============================================= -->
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="flexbox clearfix">
                        <img style="border-radius: 50%;" src="{{$teacher->picture}}" onerror="{{asset('assets_new/images/teacher.png')}}" alt="" />
                        <div class="flexcontent clearfix">
                            <div class="title clearfix">{{$teacher->first_name}} {{$teacher->last_name}}</div>
                            <div class="links clearfix">
                                <a href="#">
                                    <i class="fa fa-envelope"></i> {{$teacher->email}}
                                </a>
                                @if(auth()->check() && (auth()->user()->hasRole('student')))
                                    <a href="{{route('admin.messages',['teacher_id' => $teacher->id])}}">
                                        <i class="fa fa-comments"></i> @lang('labels.frontend.teacher.send_now')
                                    </a>
                                @elseif(!auth()->check())
                                    <a id="openLoginModal" href="#">
                                        <i class="fa fa-comments"></i> @lang('labels.frontend.teacher.send_now')
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <h3>@lang('labels.frontend.teacher.courses_by_teacher')</h3>
                </div>
            </div>
            @if(count($courses) > 0)
            <div class="row mtb-15 clearfix">
                @foreach($courses as $item)
                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <div class="best-course clearfix">
                        <a href="{{ route('courses.show', [$item->slug]) }}">
                            <div class="best-course-pic"
                                 @if($item->course_image)
                            style="background-image:url({{asset('storage/uploads/'.$item->course_image)}}) " @else
                            style="background-image:url(https://demo.neonlms.com/storage/uploads/placeholder-2.jpg) "
                                    @endif>
                                @if($item->trending == 1)
                                    <div class="trend-badge text-center text-uppercase">
                                        <i class="fa fa-bolt"></i>
                                        <span>@lang('labels.frontend.badges.trending')</span>
                                    </div>
                                @endif
                                @if($item->free == 1)
                                    <div class="trend-badge text-center text-uppercase">
                                        <i class="fa fa-bolt"></i>
                                        <span>@lang('labels.backend.courses.fields.free')</span>
                                    </div>
                                @endif

                                <div class="course-price text-center">
                                    @if($item->free == 1)
                                        {{trans('labels.backend.courses.fields.free')}}
                                    @else
                                        {{$appCurrency['symbol'].' '.$item->price}}
                                    @endif
                                </div>
                            </div>
                        </a>
                        <div class="best-course-text">
                            <div class="course-title"><a href="{{ route('courses.show', [$item->slug]) }}">{{$item->title}}</a></div>
                            <ul class="course-meta clearfix">
                                <li>{{$item->category->name}}</li>
                                <li>{{ $item->students()->count() }} @lang('labels.frontend.teacher.students')</li>
                            </ul>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
                <div class="couse-pagination text-center ul-li">
                    {{ $courses->links() }}
                </div>
            @else
                <p>@lang('labels.general.no_data_available')</p>
            @endif
        </div>
    </section>
    <!-- End  of teacher details area
        ============================================= -->

@endsection