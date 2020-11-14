@extends('frontend.layouts.app'.config('theme_layout'))

@section('title', ($blog->meta_title) ? $blog->meta_title : app_name() )
@section('meta_description', $blog->meta_description)
@section('meta_keywords', $blog->meta_keywords)

@section('content')

    <!-- Start of breadcrumb section
    ============================================= -->
    <div class="banner custom-banner-bg">
        <div class="container">
            <div class="page-heading">
                <h2>{{$blog->title}}</h2>
            </div>
        </div>
    </div>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of Blog single content
        ============================================= -->
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12 col-sm-8 col-md-9 col-lg-9 col-xl-9">
                    <div class="post-title clearfix">{{$blog->title}}</div>
                    <ul class="post-list clearfix">
                        <li><i class="fa fa-user-circle-o"></i> {{$blog->author->name}}</li>
                        <li><i class="fa fa-list"></i> <a
                                    href="{{route('blogs.category',['category' => $blog->category->slug])}}"> {{$blog->category->name}}</a></li>
                        <li><i class="fa fa-calendar"></i> {{$blog->created_at->format('d M Y')}}</li>
                    </ul>
                    @if($blog->image != "")
                    <div class="blog-detail-thumbnile mb35">
                        <img class="content-img" src="{{asset('storage/uploads/'.$blog->image)}}" alt="">
                    </div>
                    @endif

                    <p class="post-content clearfix">
                        {!! $blog->content !!}
                    </p>

                    <div class="post-heading clearfix"><span>Related</span> Articles</div>
                    <div class="row clearfix">

                        @if(count($related_news) > 0)
                            @foreach($related_news as $item)
                                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                    <div class="blog clearfix">
                                        <div class="blog-img clearfix">
                                            <a href="{{route('blogs.index',['slug'=>$item->slug.'-'.$item->id ])}}">
                                                <img src="@if($item->image != "") {{asset('storage/uploads/'.$item->image)}} @else {{asset('assets_new/images/blog-img-1.jpg')}} @endif"  alt="" />

                                            </a>
                                            <div class="blogdate">{{$item->created_at->format('d M Y')}}</div>
                                        </div>
                                        <div class="blogcontent clearfix">
                                            <div class="blogtitle"><a href="{{route('blogs.index',['slug'=>$item->slug.'-'.$item->id ])}}">{{$item->title}}</a></div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="post-heading clearfix">Post <span>Comments</span></div>
                    @if(auth()->check())
                        <div class="teacher-faq-form">
                            <form method="POST" action="{{route('blogs.comment',['id'=>$blog->id])}}"
                                  data-lead="Residential">
                                @csrf
                                <div class="form-group">
                                    <label for="comment"> @lang('labels.frontend.blog.write_a_comment')</label>
                                    <textarea name="comment" required class="mb-0" id="comment" rows="2"
                                              cols="20"></textarea>
                                    <span class="help-block text-danger">{{ $errors->first('comment', ':message') }}</span>
                                </div>

                                <div class="nws-button text-center  gradient-bg text-uppercase">
                                    <button type="submit" value="Submit"> @lang('labels.frontend.blog.add_comment')</button>
                                </div>
                            </form>
                        </div>
                    @else
                    <a id="openLoginModal" class="btn btn-primary btn-sm mtb-15 gradient-bg text-white">@lang('labels.frontend.blog.login_to_post_comment')</a>
                    @endif

                    @if($blog->comments->count() > 0)

                    <div class="blog-comment-area">
                        <ul class="comment-list my-5">
                            @foreach($blog->comments as $item)
                            <li class="d-block">
                                <div class="row full-width">
                                    <div class="comment-avater col-3">
                                        <img src="{{$item->user->picture}}" alt="">
                                    </div>

                                    <div class="col-9">
                                        <div class="row mt25">
                                            <div class="float-left col-6 author-name-rate">
                                                @lang('labels.frontend.blog.by'): <span>{{$item->name}}</span>
                                            </div>

                                            <div class="col-6">
                                                <div class="float-right ">{{$item->created_at->diffforhumans()}}</div><br>
                                                @if($item->user_id == auth()->user()->id)
                                                <div class="float-right">

                                                    <a class="text-danger font-weight-bolf" href="{{route('blogs.comment.delete',['id'=>$item->id])}}"> @lang('labels.general.delete')</a>

                                                </div>
                                                @endif
                                            </div>

                                        </div>
                                    </div>
                                    <div class="author-designation-comment col-12">
                                        <p>{{$item->comment}}</p>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    @else
                    <p class="my-5">@lang('labels.frontend.blog.no_comments_yet')</p>
                    @endif
                </div>
                @include('frontend.blogs.partials.sidebar-5')
            </div>
        </div>
    </section>
    <!-- End of Blog single content
        ============================================= -->


@endsection