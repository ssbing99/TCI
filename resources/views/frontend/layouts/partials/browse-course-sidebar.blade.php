<div class="col-12 col-sm-4 col-md-3 col-lg-3 col-xl-3">
    <form action="{{route('search-course')}}" method="get">
        <div class="sidebar clearfix">
            <div class="sidetitle clearfix">@lang('labels.frontend.home.search')</div>
            <input type="text" class="form-control" value="{{(request('q') ? request('q') : old('q'))}}"
                   id="q" name="q" placeholder="@lang('labels.frontend.layouts.partials.search_courses')..." />
        </div>

        <div class="sidebar clearfix">
            <div class="sidetitle clearfix">@lang('labels.backend.courses.fields.category')</div>
            <select name="category" class="form-control">
                <option value="">- @lang('labels.frontend.course.select_category')</option>
                @if(count($categories) > 0)
                    @foreach($categories as $category)
                        <option @if(request('category') && request('category') == $category->id) selected
                                @endif value="{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <button type="submit" class="btn btn-theme btn-lg btn-block">@lang('labels.frontend.course.find_courses')</button>
    </form>

    @if($recent_news->count() > 0)
        <div class="sidebar clearfix" style="margin-top: 30px;">
            <div class="sideheading clearfix">@lang('labels.frontend.course.recent_blog_view')</div>
            <ul class="post clearfix">
                @foreach($recent_news as $item)
                    <li>
{{--                        <div class="post-title clearfix">In in quas a eius.</div>--}}
{{--                        @if($item->image != "")--}}
{{--                            <div class="latest-news-thumbnile relative-position"--}}
{{--                                 style="background-image: url({{asset('storage/uploads/'.$item->image)}})">--}}
{{--                                <div class="blakish-overlay"></div>--}}
{{--                            </div>--}}
{{--                        @endif--}}
                        <div class="post-title clearfix"><a href="{{route('blogs.index',['slug'=>$item->slug.'-'.$item->id])}}">{{$item->title}}</a></div>
                        <dt><i class="fa fa-calendar"></i> {{$item->created_at->format('d M Y')}}</dt>
                        <p>{{substr($item->content,0, 100).'...'}}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($global_featured_course != "")
    <div class="sidebar clearfix" style="margin-top: 30px;">
        <div class="sideheading clearfix">@lang('labels.frontend.course.popular_course')</div>
        <div class="coursegrid clearfix">
            <img src="@if($global_featured_course->course_image != "") {{asset('storage/uploads/'.$global_featured_course->course_image)}} @else {{asset('assets_new/images/course-img.jpg')}} @endif"  alt="" />
            <div class="price">
                @if($global_featured_course->free == 1)
                    {{trans('labels.backend.courses.fields.free')}}
                @else
                    {{$appCurrency['symbol'].' '.$global_featured_course->price}}
                @endif
            </div>
            <h6><a href="{{ route('courses.show', [$global_featured_course->slug]) }}">{{$global_featured_course->title}}</a></h6>
            <p>{{substr($global_featured_course->description, 0,200).'...'}}</p>
            <div class="row clearfix">
                <div class="col-8 col-sm-7 col-md-7 col-lg-8 col-xl-8">
                    @foreach($global_featured_course->teachers as $teacher)
                        <div class="user-img">
                            <img src="{{$teacher->picture}}" alt="Image goes here" />
                            <p class="username">By <span><a href="#">{{$teacher->first_name}}</a></span></p>
                        </div>
                    @endforeach
                </div>
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <ul class="subicons" style="float: left;">
                        <li><i class="fa fa-users"></i> {{ $global_featured_course->students()->count() }}</li>
                        <li><i class="fa fa-commenting-o"></i> {{count($global_featured_course->reviews) }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @endif

</div>