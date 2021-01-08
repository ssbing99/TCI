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
                    <h1>Reviews</h1>
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
                    <div class="review-box clearfix">
                        @if($courses->count() > 0)

                            @foreach($courses as $course)

                                <?php
                                $skilllevel = '';

                                if($course->beginner)
                                    $skilllevel = 'Beginner';
                                elseif($course->intermediate)
                                    $skilllevel .= ($skilllevel != ''?',<br/>':'').'Intermediate';
                                elseif($course->advance)
                                    $skilllevel .= ($skilllevel != ''?',<br/>':'').'Advanced';


                                ?>
                        <div class="review-container clearfix">
                            <div class="row clearfix">
                                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                                    <div class="review-img clearfix">
                                        <a href="{{ route('courses.show', [$course->slug]) }}"><img src="@if($course->course_image != "") {{asset('storage/uploads/'.$course->course_image)}} @else {{asset('assets_new/images/course-img.jpg')}} @endif" alt="" /></a>
                                        <div class="black-bg">{{$appCurrency['symbol']}} {{$course->price}}<span><i class="fa fa-skype"></i> {{$appCurrency['symbol']}} {{$course->price_skype}}</span></div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                                    <div class="review-title clearfix"><a href="{{ route('courses.show', [$course->slug]) }}">{{$course->title}}</a></div>
                                    <p class="review-txt clearfix">{!! substr(strip_tags($course->description), 0,300).'...'!!}</p>
                                    <ul class="review-list clearfix">
                                        <li>
                                            <div class="review-items clearfix">Skill Level<span>{!! $skilllevel !!}</span></div>
                                        </li>
                                        <li>
                                            <div class="review-items clearfix">Category<span>{{ $course->category->name }}</span></div>
                                        </li>
                                        <li>
                                            <div class="review-items clearfix">Duration<span>{{ $course->duration }} Days</span></div>
                                        </li>
                                    </ul>
                                    @foreach($course->teachers as $teacher)
                                    <div class="review-instructor clearfix">
                                        <img src="{{$teacher->picture}}" alt="" /> {{$teacher->first_name}}
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                            @endforeach
                        @endif
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
                }else{
                    location.href = '{{url()->current()}}';
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