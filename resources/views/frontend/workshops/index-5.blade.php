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
                    <h1>Workshop</h1>
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
                    <div class="workshop-box clearfix">
                        <div class="row clearfix">
{{--                            <div class="col-12">--}}
{{--                                <div class="float-right">--}}
{{--                                    <select class="form-control">--}}
{{--                                        <option value="">- Currency Type -</option>--}}
{{--                                        <option value="usd">USD</option>--}}
{{--                                        <option value="euro">Euro</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <div class="col-12">
                                @if($workshops->count() > 0)
                                    @foreach($workshops as $workshop)
                                        <ul class="workshop clearfix">
                                            <li class="image">
                                                <img src="{{$workshop->getFirstImage()}}" alt="Images goes here" />
                                            </li>
                                            <li class="content">
                                                <div class="workshop-title clearfix">{{$workshop->title}}</div>
                                                <ul class="subtitle clearfix">
                                                    <li>Instructor : <span>David Bathgate</span></li>
                                                    <li>Date : <span>{{$workshop->workshop_date}}</span></li>
                                                    @if($workshop->deposit != null)
                                                    <li>Deposit : <span class="price">{{$appCurrency['symbol']}}{{$workshop->deposit}}</span></li>
                                                    @endif
                                                </ul>
                                                <p class="workshop-text clearfix">{{substr(strip_tags($workshop->description),0, 100).'...'}}</p>
                                            </li>
                                            <li class="action">
                                                <div class="courseprice clearfix">
                                                    <span>Price</span>
                                                    {{$appCurrency['symbol']}}{{$workshop->price}}
                                                </div>
                                                <center><a href="#" class="btn btn-primary br-24 btn-md">Find Out More</a></center>
                                            </li>
                                        </ul>
                                    @endforeach
                                @endif
                                <ul class="workshop clearfix">
                                    <li class="image">
                                        <img src="{{asset('assets_new/images/workshop-img-1.jpg')}}" alt="Images goes here" />
                                    </li>
                                    <li class="content">
                                        <div class="workshop-title clearfix">Capturing India's North Land: A Photography Workshop Holiday</div>
                                        <ul class="subtitle clearfix">
                                            <li>Instructor : <span>David Bathgate</span></li>
                                            <li>Date : <span>3-14 November 2020</span></li>
                                            <li>Deposit : <span class="price">$570.00</span></li>
                                        </ul>
                                        <p class="workshop-text clearfix">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                                    </li>
                                    <li class="action">
                                        <div class="courseprice clearfix">
                                            <span>Price</span>
                                            $2295.00
                                        </div>
                                        <center><a href="#" class="btn btn-primary br-24 btn-md">Find Out More</a></center>
                                    </li>
                                </ul>
                                <ul class="workshop clearfix">
                                    <li class="image">
                                        <img src="{{asset('assets_new/images/workshop-img-2.jpg')}}" alt="Images goes here" />
                                    </li>
                                    <li class="content">
                                        <div class="workshop-title clearfix">Wild Zanskar Trek</div>
                                        <ul class="subtitle clearfix">
                                            <li>Instructor : <span>David Bathgate</span></li>
                                            <li>Date : <span>3-14 November 2020</span></li>
                                        </ul>
                                        <p class="workshop-text clearfix">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                                    </li>
                                    <li class="action">
                                        <div class="courseprice clearfix">
                                            <span>Price</span>
                                            $5639.00
                                        </div>
                                        <center><a href="#" class="btn btn-primary br-24 btn-md">Find Out More</a></center>
                                    </li>
                                </ul>
                                <ul class="workshop clearfix">
                                    <li class="image">
                                        <img src="{{asset('assets_new/images/workshop-img-1.jpg')}}" alt="Images goes here" />
                                    </li>
                                    <li class="content">
                                        <div class="workshop-title clearfix">Capturing India's North Land: A Photography Workshop Holiday</div>
                                        <ul class="subtitle clearfix">
                                            <li>Instructor : <span>David Bathgate</span></li>
                                            <li>Date : <span>3-14 November 2020</span></li>
                                            <li>Deposit : <span class="price">$570.00</span></li>
                                        </ul>
                                        <p class="workshop-text clearfix">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                                    </li>
                                    <li class="action">
                                        <div class="courseprice clearfix">
                                            <span>Price</span>
                                            $2295.00
                                        </div>
                                        <center><a href="#" class="btn btn-primary br-24 btn-md">Find Out More</a></center>
                                    </li>
                                </ul>
                                <ul class="workshop clearfix">
                                    <li class="image">
                                        <img src="{{asset('assets_new/images/workshop-img-2.jpg')}}" alt="Images goes here" />
                                    </li>
                                    <li class="content">
                                        <div class="workshop-title clearfix">Wild Zanskar Trek</div>
                                        <ul class="subtitle clearfix">
                                            <li>Instructor : <span>David Bathgate</span></li>
                                            <li>Date : <span>3-14 November 2020</span></li>
                                        </ul>
                                        <p class="workshop-text clearfix">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                                    </li>
                                    <li class="action">
                                        <div class="courseprice clearfix">
                                            <span>Price</span>
                                            $5639.00
                                        </div>
                                        <center><a href="#" class="btn btn-primary br-24 btn-md">Find Out More</a></center>
                                    </li>
                                </ul>
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
            {{--var cjson = {!! $courses_json !!};--}}
            {{--console.log(cjson);--}}

            {{--$('#list').click(function(event){event.preventDefault();$('#products .item').addClass('list-group-item');});--}}
            {{--$('#grid').click(function(event){event.preventDefault();$('#products .item').removeClass('list-group-item');$('#products .item').addClass('grid-group-item');});--}}

            {{--$(document).on('change', '#sortBy', function () {--}}

            {{--    // var filterBy = $('#filterBy').val() != '' ? 'filter=' + $('#filterBy').val() : '';--}}

            {{--    if ($(this).val() != "") {--}}
            {{--        location.href = '{{url()->current()}}?type=' + $(this).val();--}}
            {{--    }--}}
            {{--});--}}

            {{--$(document).on('change', '#filterBy', function () {--}}

            {{--    var sortBy = $('#sortBy').val() != '' ? 'type=' + $('#sortBy').val() : '';--}}

            {{--    if ($(this).val() != "") {--}}
            {{--        location.href = '{{url()->current()}}?filter=' + $(this).val() + (sortBy!=""?'&'+sortBy:'');--}}
            {{--    } else {--}}
            {{--        location.href = '{{url()->current()}}'+(sortBy!=""?'?'+sortBy:'');--}}
            {{--    }--}}
            {{--});--}}

            @if(request('type') != "")
            $('#sortBy').find('option[value="' + "{{request('type')}}" + '"]').attr('selected', true);
            @endif
            @if(request('filter') != "")
            $('#filterBy').find('option[value="' + "{{request('filter')}}" + '"]').attr('selected', true);
            @endif
        });

    </script>
@endpush