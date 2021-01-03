@if($recent_news->count() > 0)
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12"><h3>Recent Blogs</h3></div>
            </div>
            <div class="row blogpost clearfix">
                @foreach($recent_news as $item)
                    <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                        <div class="blogdetails">
                            <a href="{{route('blogs.index',['slug'=>$item->slug.'-'.$item->id])}}">
                                <img class="img-fluid" alt="" src="{{asset('storage/uploads/'.$item->image)}}">
                            </a>
                            <div class="blogcontent">
                                <div class="left">
                                    <div class="date">{{$item->created_at->format('d')}}<span>{{$item->created_at->format('M')}}</span></div>
                                </div>
                                <div class="right">
                                    <p>
                                        <a href="{{route('blogs.index',['slug'=>$item->slug.'-'.$item->id])}}">{{$item->title}}</a>
                                        <span>{{substr(preg_replace('#\<(.*?)\>#', '', $item->content),0, 100).'...'}}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                    <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                        <div class="blogdetails">
                            <a href="#">
                                <img class="img-fluid" alt="Give a Kick start to your photography career with The Compelling Image" src="{{asset("assets_new/images/blog-1.jpg")}}">
                            </a>
                            <div class="blogcontent">
                                <div class="left">
                                    <div class="date">27<span>Mar</span></div>
                                </div>
                                <div class="right">
                                    <p>
                                        <a href="#">Give a Kick start to your photography career with The Compelling Image</a>
                                        <span>Give a Kick start to your photography career with TheCompellingImage "A picture speaks a thousand words" this is a universal truth about Photograph. Photography has now</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <div class="blogdetails">
                        <a href="#">
                            <img class="img-fluid" alt="How to Become a Professional food Photographer" src="{{asset("assets_new/images/blog-2.jpg")}}">
                        </a>
                        <div class="blogcontent">
                            <div class="left">
                                <div class="date">27<span>Mar</span></div>
                            </div>
                            <div class="right">
                                <p>
                                    <a href="#">How to Become a Professional food Photographer</a>
                                    <span>Have you discovered your calling for specialization in photography? Just like all other profession in the world, photography also required that you have a specialization in</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <div class="blogdetails">
                        <a href="#">
                            <img class="img-fluid" alt="Ask Yourself These 10 Questions Before Starting A Career In Photography" src="{{asset("assets_new/images/blog-3.jpg")}}">
                        </a>
                        <div class="blogcontent">
                            <div class="left">
                                <div class="date">06<span>Jul</span></div>
                            </div>
                            <div class="right">
                                <p>
                                    <a href="#">Ask Yourself These 10 Questions Before Starting A Career In Photography</a>
                                    <span>If you want to become a professional photographer, you can simply join one of the online interactive photography classes&nbsp;and sharpen your skills to be able to</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endif