{{--@if($recent_news->count() > 0)--}}
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12"><h3>Recent Blogs</h3></div>
            </div>
            <div class="row blogpost clearfix">
                @foreach($recent_news as $item)
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

            </div>
        </div>
    </section>

{{--@endif--}}