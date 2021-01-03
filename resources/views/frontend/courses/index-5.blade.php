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
     .listing-filter-form select{
            height:50px!important;
        }

        /*ul.pagination {*/
        /*    display: inline;*/
        /*    text-align: center;*/
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
    <header>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <h1>@if(isset($category)) {{$category->name}} @else @lang('labels.frontend.course.courses') @endif</h1>
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
                <div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
                    <div class="bg-f7f7f7 clearfix">
                        <form method="post" action="" role="form">
                            <div class="form-group">
                                <input type="text" name="courseSearch" id="courseSearch" class="form-control" placeholder="Search" />
                            </div>
                            <div class="form-group">
                                <input type="text" name="filterSkill" id="filterSkill" class="form-control" placeholder="Filter by Skill Level" />
                            </div>
                            <div class="form-group">
                                <input type="text" name="filterDuration" id="filterDuration" class="form-control" placeholder="Filter by Duration" />
                            </div>
                            <div class="form-group">
                                <label class="label">Course Price</label>
                                <div class="demo__body">
                                    <input id="demo_0" type="text" name="" value="" />
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-12 col-sm-8 col-md-8 col-lg-9 col-xl-9">
                    <div class="bg-f7f7f7 clearfix">
                        <div class="filter-text clearfix">
                            @if($courses->count() > 0)
                                Showing all {{$courses->count()}} Results
                            @else
                                @lang('labels.general.no_data_available')
                            @endif
                                @if($courses->count() > 0)
                                    <span class="float-right">

                                    <select class="form-control" id="sortBy">
                                    <option value="">-Sort by-</option>
                                    <option value="popularity">Popularity</option>
                                    <option value="price">Price</option>
                                    <option value="duration">Duration</option>
                                  </select>

                            </span>
                                @endif
                        </div>
                    </div>
                    <div class="row clearfix">

                        @if($courses->count() > 0)

                            @foreach($courses as $course)
                                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                    <div class="course clearfix">
                                        <div class="course-img clearfix">
                                            <a href="{{ route('courses.show', [$course->slug]) }}"><img src="@if($course->course_image != "") {{asset('storage/uploads/'.$course->course_image)}} @else {{asset('assets_new/images/course-img.jpg')}} @endif" alt="" /></a>
                                            <div class="over">
                                                <div class="price">
                                                    @if($course->free == 1)
                                                        {{trans('labels.backend.courses.fields.free')}}
                                                    @else
                                                        <span>{{$appCurrency['symbol']}}</span> {{$course->price}}
                                                        <div class="float-right">
                                                            <i class="fa fa-skype"></i>
                                                            <span>{{$appCurrency['symbol']}}</span> {{$course->price_skype}}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="course-content clearfix">
                                            <p class="title clearfix"><a href="{{ route('courses.show', [$course->slug]) }}">{{$course->title}}</a></p>
                                            <div class="desc clearfix">By :
                                                @foreach($course->teachers as $teacher){{$teacher->first_name}}&nbsp;@endforeach<span class="duration">{{$course->duration}} Days</span></div>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                        @endif

                        <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                            <div class="course clearfix">
                                <div class="course-img clearfix">
                                    <a href="course-details.html"><img src="{{asset("assets_new/images/course-1.jpg")}}" alt="Images goes here" /></a>
                                    <div class="over">
                                        <div class="price"><span>$</span> 149 <div class="float-right"><i class="fa fa-skype"></i> <span>$</span> 169</div></div>
                                    </div>
                                </div>
                                <div class="course-content clearfix">
                                    <p class="title clearfix"><a href="course-details.html">Photography Portfolio Review with David Bathgate</a></p>
                                    <div class="desc clearfix">By : David Bathgate<span class="duration">60 Days</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                            <div class="course clearfix">
                                <div class="course-img clearfix">
                                    <a href="course-details.html"><img src="{{asset("assets_new/images/course-2.jpg")}}" alt="Images goes here" /></a>
                                    <div class="over">
                                        <div class="price"><span>$</span> 159 <div class="float-right"><i class="fa fa-skype"></i> <span>$</span> 179</div></div>
                                    </div>
                                </div>
                                <div class="course-content clearfix">
                                    <p class="title clearfix"><a href="course-details.html">Art of Culinary Photography</a></p>
                                    <div class="desc clearfix">By : Meeta Khurana<span class="duration">90 Days</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                            <div class="course clearfix">
                                <div class="course-img clearfix">
                                    <a href="course-details.html"><img src="{{asset("assets_new/images/course-3.jpg")}}" alt="Images goes here" /></a>
                                    <div class="over">
                                        <div class="price"><span>$</span> 149 <div class="float-right"><i class="fa fa-skype"></i> <span>$</span> 169</div></div>
                                    </div>
                                </div>
                                <div class="course-content clearfix">
                                    <p class="title clearfix"><a href="course-details.html">Capturing Breathtaking Landscapes</a></p>
                                    <div class="desc clearfix">By : Gina Genis<span class="duration">30 Days</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                            <div class="course clearfix">
                                <div class="course-img clearfix">
                                    <a href="course-details.html"><img src="{{asset("assets_new/images/course-1.jpg")}}" alt="Images goes here" /></a>
                                    <div class="over">
                                        <div class="price"><span>$</span> 149 <div class="float-right"><i class="fa fa-skype"></i> <span>$</span> 169</div></div>
                                    </div>
                                </div>
                                <div class="course-content clearfix">
                                    <p class="title clearfix"><a href="course-details.html">Photography Portfolio Review with David Bathgate</a></p>
                                    <div class="desc clearfix">By : David Bathgate<span class="duration">60 Days</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                            <div class="course clearfix">
                                <div class="course-img clearfix">
                                    <a href="course-details.html"><img src="{{asset("assets_new/images/course-2.jpg")}}" alt="Images goes here" /></a>
                                    <div class="over">
                                        <div class="price"><span>$</span> 159 <div class="float-right"><i class="fa fa-skype"></i> <span>$</span> 179</div></div>
                                    </div>
                                </div>
                                <div class="course-content clearfix">
                                    <p class="title clearfix"><a href="course-details.html">Art of Culinary Photography</a></p>
                                    <div class="desc clearfix">By : Meeta Khurana<span class="duration">90 Days</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                            <div class="course clearfix">
                                <div class="course-img clearfix">
                                    <a href="course-details.html"><img src="{{asset("assets_new/images/course-3.jpg")}}" alt="Images goes here" /></a>
                                    <div class="over">
                                        <div class="price"><span>$</span> 149 <div class="float-right"><i class="fa fa-skype"></i> <span>$</span> 169</div></div>
                                    </div>
                                </div>
                                <div class="course-content clearfix">
                                    <p class="title clearfix"><a href="course-details.html">Capturing Breathtaking Landscapes</a></p>
                                    <div class="desc clearfix">By : Gina Genis<span class="duration">30 Days</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                            <div class="course clearfix">
                                <div class="course-img clearfix">
                                    <a href="course-details.html"><img src="{{asset("assets_new/images/course-1.jpg")}}" alt="Images goes here" /></a>
                                    <div class="over">
                                        <div class="price"><span>$</span> 149 <div class="float-right"><i class="fa fa-skype"></i> <span>$</span> 169</div></div>
                                    </div>
                                </div>
                                <div class="course-content clearfix">
                                    <p class="title clearfix"><a href="course-details.html">Photography Portfolio Review with David Bathgate</a></p>
                                    <div class="desc clearfix">By : David Bathgate<span class="duration">60 Days</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                            <div class="course clearfix">
                                <div class="course-img clearfix">
                                    <a href="course-details.html"><img src="{{asset("assets_new/images/course-2.jpg")}}" alt="Images goes here" /></a>
                                    <div class="over">
                                        <div class="price"><span>$</span> 159 <div class="float-right"><i class="fa fa-skype"></i> <span>$</span> 179</div></div>
                                    </div>
                                </div>
                                <div class="course-content clearfix">
                                    <p class="title clearfix"><a href="course-details.html">Art of Culinary Photography</a></p>
                                    <div class="desc clearfix">By : Meeta Khurana<span class="duration">90 Days</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                            <div class="course clearfix">
                                <div class="course-img clearfix">
                                    <a href="course-details.html"><img src="{{asset("assets_new/images/course-3.jpg")}}" alt="Images goes here" /></a>
                                    <div class="over">
                                        <div class="price"><span>$</span> 149 <div class="float-right"><i class="fa fa-skype"></i> <span>$</span> 169</div></div>
                                    </div>
                                </div>
                                <div class="course-content clearfix">
                                    <p class="title clearfix"><a href="course-details.html">Capturing Breathtaking Landscapes</a></p>
                                    <div class="desc clearfix">By : Gina Genis<span class="duration">30 Days</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- End of course section
        ============================================= -->

    <!-- Start of best course
   =============================================  -->
{{--    @include('frontend.layouts.partials.browse_courses2')--}}
    <!-- End of best course
            ============================================= -->


@endsection

@push('after-scripts')
    <script>
        $(document).ready(function () {
            var cjson = {!! $courses_json !!};
            console.log(cjson);

            $('#list').click(function(event){event.preventDefault();$('#products .item').addClass('list-group-item');});
            $('#grid').click(function(event){event.preventDefault();$('#products .item').removeClass('list-group-item');$('#products .item').addClass('grid-group-item');});

            $(document).on('change', '#sortBy', function () {

                // var filterBy = $('#filterBy').val() != '' ? 'filter=' + $('#filterBy').val() : '';

                if ($(this).val() != "") {
                    location.href = '{{url()->current()}}?type=' + $(this).val();
                }
            });

            $(document).on('change', '#filterBy', function () {

                var sortBy = $('#sortBy').val() != '' ? 'type=' + $('#sortBy').val() : '';

                if ($(this).val() != "") {
                    location.href = '{{url()->current()}}?filter=' + $(this).val() + (sortBy!=""?'&'+sortBy:'');
                } else {
                    location.href = '{{url()->current()}}'+(sortBy!=""?'?'+sortBy:'');
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