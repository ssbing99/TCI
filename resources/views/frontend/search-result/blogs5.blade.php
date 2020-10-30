@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.blog.title').' | '.app_name())

@push('after-styles')
    <style>
        /*.couse-pagination li.active {*/
        /*    color: #333333!important;*/
        /*    font-weight: 700;*/
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
        /*    background-color:white;*/
        /*    border:none;*/

        /*}*/
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
                @if(isset($q)) "{{$q}}" @endif  <span>@lang('labels.frontend.article_video.title')</span>
            </div>
        </div>
    </div>
    <!-- End of breadcrumb section
        ============================================= -->

    <!-- Start of blog content
        ============================================= -->
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12 col-sm-8 col-md-9 col-lg-9 col-xl-9">
                    <div class="row clearfix">
                        <!-- row -->
                        @if(count($blogs) > 0)
                            @foreach($blogs as $item)
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <div class="blog clearfix">
                                        <div class="blog-img clearfix">
                                            <a href="#"><img src="@if($item->image != "") {{asset('storage/uploads/'.$item->image)}} @else {{asset('assets_new/images/blog-img-1.jpg')}} @endif"  alt="" /></a>
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
                            <h3>@lang('labels.general.no_data_available')</h3>
                    @endif

                    <!-- end row -->
                    </div>
                    <nav aria-label="Page navigation example">
                        {{ $blogs->links() }}
                    </nav>
                </div>

                <!-- Start of sidebar section
                    ============================================= -->

            @include('frontend.blogs.partials.sidebar-5')

            <!-- End of sidebar section
                    ============================================= -->

            </div>
        </div>
    </section>
    <!-- End of blog content
        ============================================= -->

    <!-- Start of best course
   =============================================  -->
    @include('frontend.layouts.partials.browse_courses2')
    <!-- End of best course
            ============================================= -->

@endsection