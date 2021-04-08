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
                    <h1>Gift a Course</h1>
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
                    <p class="course-txt clearfix">
                        With courses ranging from beginner through aspiring professional levels, you’re sure to find just the right online and interactive TCI course for the photographer on your gift-giving list. Choose from two-, four-, or six-lesson courses, with or without an optional 30-minute instructor consultation call on Skype. Portfolio reviews and one-on-one mentorships are available, too.<br /><br />
                        Here’s how it works. If you’re not already a TCI acount holder, you’ll be prompted to sign up for free. You’re then set to place your order. Choose the course category (two-, four-, six-lesson, portfolio review or one-on-one mentorship) you wish to purchase, make your secure payment using Pay Pal or a major credit card (Stripe Gateway), and select the date you’d like the recipient to be notified of their gift arrival. On that date, they’ll receive email notification of their gift and a code number for enrolling (within category) on the offering of their choice. That’s it! They’ll then be set - straight away - to begin learning and practicing a whole new set of photography skills and techniques, taught at TCI!
                    </p>
                </div>
                <div class="col-12">
                    <div class="text-center">
                        <div class="form-group">
                            <form class="gift-page-form" action="{{route('gifts.purchase')}}" accept-charset="UTF-8" method="post">
                                    @csrf
                                <input name="utf8" type="hidden" value="✓">
                                <label>Choose your Gift</label>

                                @if(count($gifts) > 0)
                                <div class="input-group mb-3">
                                    <select class="form-control" id="inputGroupSelect02" name="gift_id" onchange="selectionChange()">
                                        @foreach($gifts as $item)
                                            <option data-price="{{$item->price}}" value="{{$item->id}}">{{$item->title}}</option>
                                        @endforeach
{{--                                        <option data-price="69.00" value="4">2 lesson course</option>--}}
{{--                                        <option data-price="79.00" value="5">2 lesson course with skype session</option>--}}
{{--                                        <option data-price="149.00" value="6">4 lesson course</option>--}}
{{--                                        <option data-price="159.00" value="7">4 lesson course with Skype session</option>--}}
{{--                                        <option data-price="169.00" value="9">6 lesson course</option>--}}
{{--                                        <option data-price="179.00" value="10">6 lesson course with Skype session</option>--}}
{{--                                        <option data-price="179.00" value="11"> Photography Portfolio Review with David Bathgate</option>--}}
{{--                                        <option data-price="149.00" value="12">Mentorship for 1 Month</option>--}}
{{--                                        <option data-price="159.00" value="14">Mentorship for 1 Month with skype session</option>--}}
{{--                                        <option data-price="249.00" value="15">Mentorship for 3 Month</option>--}}
{{--                                        <option data-price="259.00" value="16">Mentorship for 3 Month  with skype session</option>--}}
{{--                                        <option data-price="349.00" value="17">Mentorship for 6 Month </option>--}}
{{--                                        <option data-price="359.00" value="18">Mentorship for 6 Month with skype session</option>--}}
                                    </select>
                                    <input type="text" name="price" id="price" placeholder="$69.00" disabled />
                                    <div class="input-group-append">
                                        @if(!auth()->check())
                                            <a id="openLoginModal"
                                               class="btn btn-primary"
                                               data-target="#myModal" href="#">SIGN UP</a>
                                        @elseif(auth()->check() && (auth()->user()->hasRole('student')))
                                        <button class="btn btn-primary" type="submit">BUY</button>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </form>
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
        function selectionChange(){
            var sel = document.getElementById('inputGroupSelect02');
            var inpPrice = document.getElementById('price');

            var opt = sel.options;
            var price = opt[sel.selectedIndex].getAttribute('data-price');

            inpPrice.value = '{{$appCurrency['symbol']}}'+price;
        }

        $(document).ready(function () {
            selectionChange();
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