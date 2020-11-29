<section class="grey-bg clearfix">
    <div class="container">
        <div class="row clearfix">
            <div class="col-12">
                <div class="subheading clearfix">@lang('labels.frontend.layouts.partials.browse_our')</div>
                <div class="heading clearfix">@lang('labels.frontend.layouts.partials.featured_courses')</div>
            </div>
        </div>
        <div class="row clearfix">
            @if($featured_courses->count() > 0)
                @foreach($featured_courses as $course)
                    <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                        <div class="coursegrid clearfix">
                            <a href="{{ route('courses.show', [$course->slug]) }}"><img src="@if($course->course_image != "") {{asset('storage/uploads/'.$course->course_image)}} @else {{asset('assets_new/images/course-img.jpg')}} @endif"  alt="" /></a>
                            <div class="price">
                                @if($course->free == 1)
                                    {{trans('labels.backend.courses.fields.free')}}
                                @else
                                    {{$appCurrency['symbol'].' '.$course->price}}
                                @endif
                            </div>
                            <h6><a href="{{ route('courses.show', [$course->slug]) }}">{{$course->title}}</a></h6>
                            <p>{{substr($course->description, 0,200).'...'}}</p>
                            <div class="row clearfix">
                                <div class="col-8 col-sm-7 col-md-7 col-lg-8 col-xl-8">
                                    @foreach($course->teachers as $teacher)
                                        <div class="user-img">
                                            <img src="{{$teacher->picture}}" alt="Image goes here" />
                                            <p class="username">By&nbsp;<span><a href="#">{{$teacher->first_name}}</a></span></p>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <ul class="subicons" style="float: left;">
                                        <li><i class="fa fa-users"></i> {{ $course->students()->count() }}</li>
                                        <li><i class="fa fa-commenting-o"></i> {{count($course->reviews) }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                @endforeach
            @else
                <h3>@lang('labels.general.no_data_available')</h3>
            @endif

        </div>
    </div>
</section>
