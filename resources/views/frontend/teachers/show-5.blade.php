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
    <header>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <h1>{{$teacher->first_name}} {{$teacher->last_name}}</h1>
                </div>
            </div>
        </div>
    </header>
    <!-- End of breadcrumb section
        ============================================= -->

    <?php
    $teacherProfile = $teacher->teacherProfile?:'';
    ?>
    <!-- Start of teacher details area
        ============================================= -->
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <div class="ins-bg clearfix">
                        <img src="{{$teacher->picture}}" alt="" />
                        <div class="ins-footer clearfix">
                            <div class="ins-name clearfix">{{$teacher->first_name}} {{$teacher->last_name}}</div>
                            <ul class="slinks clearfix">
                                @if(isset($teacherProfile->facebook_link))
                                    <li><a href="{{$teacherProfile->facebook_link}}"><i class="fa fa-facebook"></i></a></li>
                                @endif
                                @if(isset($teacherProfile->insta_link))
                                    <li><a href="{{$teacherProfile->insta_link}}"><i class="fa fa-instagram"></i></a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                    <div class="page-title clearfix">{{$teacher->first_name}} {{$teacher->last_name}}</div>
                    <hr />
                    <p class="course-txt clearfix">
                        {!! $teacherProfile->description !!}
                    </p>
                    <div class="page-title clearfix">Currently Teaching</div>
                    <hr />
                    @if(count($courses) > 0)
                    <div class="row mtb-15 clearfix">
                        @foreach($courses as $item)
                            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                <div class="course clearfix">
                                    <div class="course-img clearfix">
                                        <a href="{{ route('courses.show', [$item->slug]) }}"><img src="@if($item->course_image != "") {{asset('storage/uploads/'.$item->course_image)}} @else {{asset('assets_new/images/course-img.jpg')}} @endif" alt="" /></a>
                                        <div class="over">
                                            <div class="price">
                                                @if($item->free == 1)
                                                    {{trans('labels.backend.courses.fields.free')}}
                                                @else
                                                    <span>{{$appCurrency['symbol']}}</span> {{$item->price}}
                                                    <div class="float-right">
                                                        <i class="fa fa-skype"></i>
                                                        <span>{{$appCurrency['symbol']}}</span> {{$item->price}}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="course-content clearfix">
                                        <p class="title clearfix"><a href="{{ route('courses.show', [$item->slug]) }}">{{$item->title}}</a></p>
                                        <div class="desc clearfix">By :
                                            @foreach($item->teachers as $teacher){{$teacher->first_name}}&nbsp;@endforeach<span class="duration">{{$item->duration}} Days</span></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @else
                    <p>@lang('labels.general.no_data_available')</p>
                @endif
            </div>
        </div>
    </section>
    <!-- End  of teacher details area
        ============================================= -->

    @include('frontend.layouts.partials.our_teachers');

@endsection