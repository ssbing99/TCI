@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )

@push('after-styles')
    <style>
        /*.couse-pagination li.active {*/
        /*    color: #333333 !important;*/
        /*    font-weight: 700;*/
        /*}*/

        /*.ul-li ul li {*/
        /*    list-style: none;*/
        /*    display: inline-block;*/
        /*}*/

        /*.couse-pagination li {*/
        /*    font-size: 18px;*/
        /*    color: #bababa;*/
        /*    margin: 0 5px;*/
        /*}*/

        /*.disabled {*/
        /*    cursor: not-allowed;*/
        /*    pointer-events: none;*/
        /*    opacity: 0.6;*/
        /*}*/

        /*.page-link {*/
        /*    position: relative;*/
        /*    display: block;*/
        /*    padding: .5rem .75rem;*/
        /*    margin-left: -1px;*/
        /*    line-height: 1.25;*/
        /*    color: #c7c7c7;*/
        /*    background-color: white;*/
        /*    border: none;*/
        /*}*/

        /*.page-item.active .page-link {*/
        /*    z-index: 1;*/
        /*    color: #333333;*/
        /*    background-color: white;*/
        /*    border: none;*/

        /*}*/

        /*ul.pagination {*/
        /*    display: inline;*/
        /*    text-align: center;*/
        /*}*/
        select.form-control.listing-filter-form.select {
            height: unset;
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
    <div class="banner custom-banner-bg">
        <div class="container">
            <div class="page-heading">
                {{($q) ? "$q" : '' }} <span>{{trans('labels.frontend.home.search')}}</span>
            </div>
        </div>
    </div>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of course section
        ============================================= -->
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <h3>@lang('labels.frontend.course.courses')</h3>
                </div>
            </div>
            <div class="row mtb-30 clearfix @if($courses->count() > 0) search-all-scroll @endif">
                <div class="col-12">
                    @if(session()->has('success'))
                        <div class="alert alert-dismissable alert-success fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{session('success')}}
                        </div>
                    @endif
{{--                    <div class="page-title clearfix">--}}
{{--                        <div class="row clearfix">--}}
{{--                            <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">--}}
{{--                                <label class="title">@lang('labels.frontend.search_result.sort_by')--}}
{{--                                    <select id="sortBy" class="form-control" style="margin-left: 10px;">--}}
{{--                                        <option value="">@lang('labels.frontend.search_result.none')</option>--}}
{{--                                        <option value="popular">@lang('labels.frontend.search_result.popular')</option>--}}
{{--                                        <option value="trending">@lang('labels.frontend.search_result.trending')</option>--}}
{{--                                        <option value="featured">@lang('labels.frontend.search_result.featured')</option>--}}
{{--                                    </select>--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                            <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">--}}
{{--                                <label class="title">@lang('labels.frontend.course.filter_by')--}}
{{--                                    <select id="filterBy" class="form-control" style="margin-left: 10px;">--}}
{{--                                        <option value="">@lang('labels.frontend.course.none')</option>--}}
{{--                                        <option value="past">@lang('labels.frontend.course.past')</option>--}}
{{--                                        <option value="upcoming">@lang('labels.frontend.course.upcoming')</option>--}}
{{--                                    </select>--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                            <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">--}}
{{--                                <div class="float-right">--}}
{{--                                    <div class="btn-group">--}}
{{--                                        <button class="btn btn-outline-secondary" id="list">--}}
{{--                                            <i class="fa fa-list"></i>--}}
{{--                                        </button>--}}
{{--                                        <button class="btn btn-outline-secondary" id="grid">--}}
{{--                                            <i class="fa fa-th-large"></i>--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div id="products" class="row view-group">

                        @if($courses->count() > 0)

                            @foreach($courses as $course)
                                <div class="item col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                    <div class="coursegrid clearfix">
                                        <a href="{{ route('courses.show', [$course->slug]) }}"><img src="{{asset('storage/uploads/'.$course->course_image)}}" alt="" /></a>
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
                            <h4>@lang('labels.general.no_search_results')</h4>
                        @endif


                    </div>
                    <div class="couse-pagination text-center ul-li">
{{--                        {{ $courses->links() }}--}}
                    </div>
                </div>

                <!-- Start of sidebar section
                    ============================================= -->


            <!-- End of sidebar section
                    ============================================= -->
            </div>
        </div>
    </section>

{{--    <!-- End of course section--}}
{{--        ============================================= -->--}}

    <hr/>

{{--    <!-- Start of blog content--}}
{{--        ============================================= -->--}}
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <h3>@lang('labels.frontend.blog.title')</h3>
                </div>
            </div>
            <div class="row mtb-30 clearfix @if(count($blogs) > 0) search-all-scroll @endif">
                <div class="col-12">
                    <div class="row clearfix">
                        <!-- row -->
                        @if(count($blogs) > 0)
                            @foreach($blogs as $item)
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <div class="blog clearfix" style="border-bottom: none;">
                                        <div class="blog-img clearfix">
                                            <a href="{{route('blogs.index',['slug'=> $item->slug.'-'.$item->id])}}"><img src="@if($item->image != "") {{asset('storage/uploads/'.$item->image)}} @else {{asset('assets_new/images/blog-img-1.jpg')}} @endif"  alt="" /></a>
                                            <div class="blogdate">{{$item->created_at->format('d M Y')}}</div>
                                        </div>
                                        <div class="blogcontent clearfix">
                                            <div class="blogtitle"><a href="{{route('blogs.index',['slug'=> $item->slug.'-'.$item->id])}}">{{$item->title}}</a></div>
                                            <p>{!!  strip_tags(mb_substr($item->content,0,100).'...')  !!}</p>
                                            <a href="{{route('blogs.index',['slug'=> $item->slug.'-'.$item->id])}}" class="link">@lang('labels.general.read_more') <i class="fa fa-chevron-circle-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <h4>@lang('labels.general.no_search_results')</h4>
                    @endif

                    <!-- end row -->
                    </div>
                    <nav aria-label="Page navigation example">
{{--                        {{ $blogs->links() }}--}}
                    </nav>
                </div>

            </div>
        </div>
    </section>
    <!-- End of blog content
        ============================================= -->

<hr/>

    <!-- Start of store section
    ============================================= -->
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <h3>@lang('labels.frontend.store.store')</h3>
                </div>
            </div>
            <div class="row mtb-30 clearfix @if($storeItems->count() > 0) search-all-scroll @endif">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    @if(session()->has('success'))
                        <div class="alert alert-dismissable alert-success fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{session('success')}}
                        </div>
                @endif
                <!--<div class="page-title clearfix">
                    <div class="row clearfix">
                        <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                            <label class="title">@lang('labels.frontend.course.sort_by')
                        <select id="sortBy" class="form-control" style="margin-left: 10px;">
                            <option value="">@lang('labels.frontend.course.none')</option>
                                    <option value="popular">@lang('labels.frontend.course.popular')</option>
                                    <option value="trending">@lang('labels.frontend.course.trending')</option>
                                    <option value="featured">@lang('labels.frontend.course.featured')</option>
                                </select>
                            </label>
                        </div>
                    </div>
                </div>-->

                    <div id="products" class="row mtb-15 clearfix">
                        @if($storeItems->count() > 0)
                            @foreach($storeItems as $item)
                                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                <!--<img src="@if($item->item_image != "") {{asset('storage/uploads/'.$item->item_image)}} @else {{asset('assets_new/images/course-img.jpg')}} @endif"  alt="" />
                            <div class="price">
                                {{$appCurrency['symbol'].' '.$item->price}}
                                        </div>-->

                                    <div class="storegrid clearfix">
                                        <a href="{{ route('store.show', [$item->slug]) }}" >
                                            <img src="@if($item->item_image != "") {{asset('storage/uploads/'.$item->item_image)}} @else {{asset('assets_new/images/course-img.jpg')}} @endif" alt="" />
                                            <div class="overlay"></div>
                                        </a>
                                        @if($item->discount > 0)
                                            <div class="tag">Discount</div>
                                        @endif
                                        <span>
                                    {{$appCurrency['symbol'].' '.$item->price}}
                                            @if(auth()->check())
                                                <form action="{{ route('cart.addToCart') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="storeItem_id" value="{{ $item->id }}"/>
                                        <input type="hidden" name="amount" value="{{ $item->price}}"/>
                                        <a href="#" onclick="$(this).closest('form').submit()" class="btn btn-theme btn-sm"><i class="fa fa-shopping-cart"></i>@lang('labels.frontend.course.add_to_cart')</a>
                                    </form>
                                            @else
                                                <a id="openLoginModal" data-target="#myModal" href="#" class="btn btn-theme btn-sm"><i class="fa fa-shopping-cart"></i>@lang('labels.frontend.course.add_to_cart')</a>
                                            @endif
                                </span>
                                    </div>
                                    <div class="bg-f4f6f6 clearfix">
                                        <div class="storetitle clearfix">{{ $item->title }}</div>
                                        <a href="{{ route('store.show', [$item->slug]) }}" class="btn btn-theme btn-sm">View Details</a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <h4>@lang('labels.general.no_search_results')</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- End of store section
        ============================================= -->


    <!-- Start of All content
        ============================================= -->
{{--    <section>--}}
{{--        <div class="container">--}}
{{--            <div class="row clearfix">--}}
{{--                <div class="col-12">--}}
{{--                    <div class="row clearfix">--}}
{{--                        <!-- row -->--}}
{{--                        @if(count($consolidate) > 0)--}}
{{--                            @foreach($consolidate as $item)--}}
{{--                                @if($item->class === 'Course')--}}
{{--                                    <div class="item col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">--}}
{{--                                        <div class="coursegrid clearfix">--}}
{{--                                            <a href="{{ route('courses.show', [$item->slug]) }}"><img src="{{asset('storage/uploads/'.$item->course_image)}}" alt="" /></a>--}}
{{--                                            <div class="price">--}}
{{--                                                @if($item->free == 1)--}}
{{--                                                    {{trans('labels.backend.courses.fields.free')}}--}}
{{--                                                @else--}}
{{--                                                    {{$appCurrency['symbol'].' '.$item->price}}--}}
{{--                                                @endif--}}
{{--                                            </div>--}}
{{--                                            <h6><a href="{{ route('courses.show', [$item->slug]) }}">{{$item->title}}</a></h6>--}}
{{--                                            <p>{{substr($item->description, 0,200).'...'}}</p>--}}
{{--                                            <div class="row clearfix">--}}
{{--                                                <div class="col-8 col-sm-7 col-md-7 col-lg-8 col-xl-8">--}}
{{--                                                    @foreach($item->teachers as $teacher)--}}
{{--                                                        <div class="user-img">--}}
{{--                                                            <img src="{{$teacher->picture}}" alt="Image goes here" />--}}
{{--                                                            <p class="username">By&nbsp;<span><a href="#">{{$teacher->first_name}}</a></span></p>--}}
{{--                                                        </div>--}}
{{--                                                    @endforeach--}}
{{--                                                </div>--}}
{{--                                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">--}}
{{--                                                    <ul class="subicons" style="float: left;">--}}
{{--                                                        <li><i class="fa fa-users"></i> {{ $item->students()->count() }}</li>--}}
{{--                                                        <li><i class="fa fa-commenting-o"></i> {{count($item->reviews) }}</li>--}}
{{--                                                    </ul>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @elseif($item->class === 'Item')--}}
{{--                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">--}}
{{--                                    <div class="blog clearfix">--}}
{{--                                        <div class="blog-img clearfix">--}}
{{--                                            <a href="{{route('blogs.index',['slug'=> $item->slug.'-'.$item->id])}}"><img src="@if($item->image != "") {{asset('storage/uploads/'.$item->image)}} @else {{asset('assets_new/images/blog-img-1.jpg')}} @endif"  alt="" /></a>--}}
{{--                                            <div class="blogdate">{{$item->created_at->format('d M Y')}}</div>--}}
{{--                                        </div>--}}
{{--                                        <div class="blogcontent clearfix">--}}
{{--                                            <div class="blogtitle"><a href="{{route('blogs.index',['slug'=> $item->slug.'-'.$item->id])}}">{{$item->title}}</a></div>--}}
{{--                                            <p>{!!  strip_tags(mb_substr($item->content,0,100).'...')  !!}</p>--}}
{{--                                            <a href="{{route('blogs.index',['slug'=> $item->slug.'-'.$item->id])}}" class="link">@lang('labels.general.read_more') <i class="fa fa-chevron-circle-right"></i></a>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                @elseif($item->class === 'Blog')--}}
{{--                                    <div class="item col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">--}}
{{--                                        <div class="blog clearfix">--}}
{{--                                            <div class="blog-img clearfix">--}}
{{--                                                <a href="{{route('blogs.index',['slug'=> $item->slug.'-'.$item->id])}}"><img src="@if($item->image != "") {{asset('storage/uploads/'.$item->image)}} @else {{asset('assets_new/images/blog-img-1.jpg')}} @endif"  alt="" /></a>--}}
{{--                                                <div class="blogdate">{{$item->created_at->format('d M Y')}}</div>--}}
{{--                                            </div>--}}
{{--                                            <div class="blogcontent clearfix">--}}
{{--                                                <div class="blogtitle"><a href="{{route('blogs.index',['slug'=> $item->slug.'-'.$item->id])}}">{{$item->title}}</a></div>--}}
{{--                                                <p>{!!  strip_tags(mb_substr($item->content,0,100).'...')  !!}</p>--}}
{{--                                                <a href="{{route('blogs.index',['slug'=> $item->slug.'-'.$item->id])}}" class="link">@lang('labels.general.read_more') <i class="fa fa-chevron-circle-right"></i></a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            @endforeach--}}
{{--                        @else--}}
{{--                            <h3>@lang('labels.general.no_search_results')</h3>--}}
{{--                    @endif--}}

{{--                    <!-- end row -->--}}
{{--                    </div>--}}
{{--                    <nav aria-label="Page navigation example">--}}
{{--                        {{ $consolidate->links() }}--}}
{{--                    </nav>--}}
{{--                </div>--}}

{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}
    <!-- End of All content
        ============================================= -->

    <!-- Start of best course
   =============================================  -->
    @include('frontend.layouts.partials.browse_courses2')
    <!-- End of best course
            ============================================= -->



@endsection

@push('after-scripts')
    <script>
        $(document).ready(function () {
            $('#list').click(function(event){event.preventDefault();$('#products .item').addClass('list-group-item');});
            $('#grid').click(function(event){event.preventDefault();$('#products .item').removeClass('list-group-item');$('#products .item').addClass('grid-group-item');});

            $(document).on('change', '#sortBy', function () {

                var filterBy = $('#filterBy').val() != '' ? 'filter=' + $('#filterBy').val() : '';

                if ($(this).val() != "") {
                    var url;
                    @if(request('type'))
                        url = '{{url()->full()}}';

                        url = url.replace('type=' + '{{request('type')}}', 'type=' + $(this).val());
                        url = url.replace(/&amp;/g, '&');

                        @if(request('filter'))
                        url = url.replace('filter=' + '{{request('filter')}}', (filterBy!=""?filterBy:''));
                        @endif

                        location.href = url.replace('&amp;', '&');
                    @else
                        url = '{{url()->full()}}&type=' + $(this).val();
                        url = url.replace(/&amp;/g, '&');

                        @if(request('filter'))
                            url = url.replace('filter=' + '{{request('filter')}}', (filterBy!=""?filterBy:''));
                        @endif

                        location.href = url;
                    @endif
                } else {
                    url = '{{url()->full()}}';
                    url = url.replace('type=' + '{{request('type')}}', '');
                    url = url.replace(/&amp;/g, '&');

                        @if(request('filter'))
                        url = url.replace('filter=' + '{{request('filter')}}', (filterBy!=""?filterBy:''));
                        @endif

                    location.href = url;
                }
            });

            $(document).on('change', '#filterBy', function () {

                var sortBy = $('#sortBy').val() != '' ? 'type=' + $('#sortBy').val() : '';

                if ($(this).val() != "") {
                    var url;
                    @if(request('filter'))
                        url = '{{url()->full()}}';

                        url = url.replace('filter=' + '{{request('filter')}}', 'filter=' + $(this).val());
                        url = url.replace(/&amp;/g, '&');

                        @if(request('type'))
                        url = url.replace('type=' + '{{request('type')}}', (sortBy!=""?sortBy:''));
                        @endif

                        location.href = url.replace('&amp;', '&');
                    @else
                        url = '{{url()->full()}}&filter=' + $(this).val();
                        url = url.replace(/&amp;/g, '&');

                        @if(request('type'))
                            url = url.replace('type=' + '{{request('type')}}', (sortBy!=""?sortBy:''));
                        @endif

                        location.href = url;
                    @endif
                } else {
                    url = '{{url()->full()}}';
                    url = url.replace('filter=' + '{{request('filter')}}', '');
                    url = url.replace(/&amp;/g, '&');

                        @if(request('type'))
                        url = url.replace('type=' + '{{request('type')}}',  (sortBy!=""?sortBy:'{{request('type')}}'));
                        @endif

                    location.href = url;
                }
            });

            @if(request('type') != "")
            $('#sortBy').find('option[value="' + "{{request('type')}}" + '"]').attr('selected', true);
            @endif
            @if(request('filter') != "")
            $('#filterBy').find('option[value="' + "{{request('filter')}}" + '"]').attr('selected', true);
            @endif
        });

    </script>
@endpush
