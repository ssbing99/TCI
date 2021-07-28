@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.blog.title').' | '.app_name())

@push('after-styles')
    <style>
        /*.couse-pagination li.active {*/
        /*    color: #333333!important;*/
        /*    font-weight: 700;*/
        /*}*/
        .page-link {
            position: relative;
            display: block;
            padding: .5rem .75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #c7c7c7;
            background-color: white;
            border: none;
        }
        .page-item.active .page-link {
            z-index: 1;
            color: #333333;
            background-color:white;
            border:none;

        }
        /*ul.pagination{*/
        /*    display: inline;*/
        /*    text-align: center;*/
        /*}*/
        /*.cat-item.active{*/
        /*    background: black;*/
        /*    color: white;*/
        /*    font-weight: bold;*/
        /*}*/

        .page-item.active .page-link {
            color: #e30613;
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
                    <h1>@if(isset($category)){{$category->name}} @elseif(isset($tag)) {{$tag->name}} @endif  <span>@lang('labels.frontend.blog.title')</span></h1>
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
                <div class="col-12">
                    <div class="row blogpost clearfix">
                        <!-- row -->
                        @if(count($blogs) > 0)
                            @foreach($blogs as $item)
                                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                    <div class="blogdetails">
                                        <a href="{{route('blogs.index',['slug'=> $item->slug.'-'.$item->id])}}"><img class="img-fluid" src="@if($item->image != "") {{asset('storage/uploads/'.$item->image)}} @else {{asset('assets_new/images/blog-1.jpg')}} @endif"  alt="" /></a>
                                        <div class="blogcontent">
                                            <div class="left">
                                                <div class="date">{{$item->created_at->format('d')}}<span>{{$item->created_at->format('M')}}</span></div>
                                            </div>
                                            <div class="right">
                                                <p>
                                                    <a href="{{route('blogs.index',['slug'=> $item->slug.'-'.$item->id])}}">{{$item->title}}</a>
                                                    <span>{!!  mb_substr(strip_tags($item->content),0, 150).'...'  !!}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                        @else
                            <h3>@lang('labels.general.no_data_available')</h3>
                    @endif

                    <!-- end row -->
                    </div>
                    <br/>
                    <nav aria-label="Page navigation example clearfix">
                        {{ $blogs->links() }}
                    </nav>
{{--                    <div class="row blogpost clearfix">--}}

{{--                        <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">--}}
{{--                            <div class="blogdetails">--}}
{{--                                <a href="#">--}}
{{--                                    <img class="img-fluid" alt="Give a Kick start to your photography career with The Compelling Image" src="{{asset("assets_new/images/blog-1.jpg")}}">--}}
{{--                                </a>--}}
{{--                                <div class="blogcontent">--}}
{{--                                    <div class="left">--}}
{{--                                        <div class="date">27<span>Mar</span></div>--}}
{{--                                    </div>--}}
{{--                                    <div class="right">--}}
{{--                                        <p>--}}
{{--                                            <a href="#">Give a Kick start to your photography career with The Compelling Image</a>--}}
{{--                                            <span>Give a Kick start to your photography career with TheCompellingImage "A picture speaks a thousand words" this is a universal truth about Photograph. Photography has now</span>--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">--}}
{{--                            <div class="blogdetails">--}}
{{--                                <a href="#">--}}
{{--                                    <img class="img-fluid" alt="How to Become a Professional food Photographer" src="{{asset("assets_new/images/blog-2.jpg")}}">--}}
{{--                                </a>--}}
{{--                                <div class="blogcontent">--}}
{{--                                    <div class="left">--}}
{{--                                        <div class="date">27<span>Mar</span></div>--}}
{{--                                    </div>--}}
{{--                                    <div class="right">--}}
{{--                                        <p>--}}
{{--                                            <a href="#">How to Become a Professional food Photographer</a>--}}
{{--                                            <span>Have you discovered your calling for specialization in photography? Just like all other profession in the world, photography also required that you have a specialization in</span>--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">--}}
{{--                            <div class="blogdetails">--}}
{{--                                <a href="#">--}}
{{--                                    <img class="img-fluid" alt="Ask Yourself These 10 Questions Before Starting A Career In Photography" src="{{asset("assets_new/images/blog-3.jpg")}}">--}}
{{--                                </a>--}}
{{--                                <div class="blogcontent">--}}
{{--                                    <div class="left">--}}
{{--                                        <div class="date">06<span>Jul</span></div>--}}
{{--                                    </div>--}}
{{--                                    <div class="right">--}}
{{--                                        <p>--}}
{{--                                            <a href="#">Ask Yourself These 10 Questions Before Starting A Career In Photography</a>--}}
{{--                                            <span>If you want to become a professional photographer, you can simply join one of the online interactive photography classes&nbsp;and sharpen your skills to be able to</span>--}}
{{--                                        </p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}
                </div>
            </div>
        </div>
    </section>
    <!-- End of blog content
        ============================================= -->

@endsection