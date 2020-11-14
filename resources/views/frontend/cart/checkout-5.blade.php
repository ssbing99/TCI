@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.cart.payment_status').' | '.app_name())

@push('after-styles')
    <style>
        input[type="radio"] {
            display: inline-block !important;
        }

        .course-rate li {
            color: #ffc926 !important;
        }

        #applyCoupon {
            box-shadow: none !important;
            color: #fff !important;
            font-weight: bold;
        }

        #coupon.warning {
            border: 1px solid red;
        }

        .purchase-list .in-total {
            font-size: 18px;
        }

        #coupon-error {
            color: red;
        }
        .in-total:not(:first-child):not(:last-child){
            font-size: 15px;
        }

    </style>

    <script src='https://js.stripe.com/v2/' type='text/javascript'></script>
@endpush
@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    <div class="banner custom-banner-bg">
        <div class="container">
            <div class="page-heading text-sm-center">
                @lang('labels.frontend.cart.cart')
            </div>
        </div>
    </div>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of Checkout content
        ============================================= -->
    <section id="checkout" class="checkout-section">
        <div class="container">
            <div class="row clearfix">
                <div class="col-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                    @if(session()->has('danger'))
                        <div class="alert alert-dismissable alert-danger fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {!! session('danger')  !!}
                        </div>
                    @endif
                        @if(count($courses) > 0 || count($storeItems) > 0)
                            @if (count($courses) > 0)
                                @foreach($courses as $course)

                                <div class="cartbox clearfix">
                                    <div class="cartbtn clearfix"><a href="{{route('cart.remove',['course'=>$course])}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></div>
                                    <div class="row clearfix">
                                        <div class="col-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                            <img src="{{asset('storage/uploads/'.$course->course_image)}}" alt="" class="img-full" />
                                        </div>
                                        <div class="col-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
                                            <div class="cart-title clearfix">{{$course->title}}</div>
                                            <ul class="cartdetails clearfix">
                                                <li>@lang('labels.frontend.course.teacher')
                                                    @foreach($course->teachers as $key=>$teacher)
                                                    @php $key++ @endphp
                                                    <span>{{$teacher->full_name}}</span>
                                                    @if($key < count($course->teachers )) <br/> @endif
                                                    @endforeach
                                                </li>
                                                <li>@lang('labels.frontend.course.ratings')<span>
                                                                    <div class="stars">
                                                                        @for($i=1; $i<=(int)$course->rating; $i++)
                                                                            <i class="fa fa-star"></i>
                                                                        @endfor
                                                                    </div></span></li>
                                                <li>@lang('labels.frontend.course.category')<span>{{$course->category->name}}</span></li>
                                            </ul>
                                            <div class="cart-price clearfix">
                                                @if($course->free == 1)
                                                <span>{{trans('labels.backend.bundles.fields.free')}}</span>
                                                @else
                                                <span> {{$appCurrency['symbol'].' '.$course->price}}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                            <div class="cartblock clearfix">
                                                <div class="cart-qty clearfix">Quantity</div>
                                                <div class="input-group mtb-10">1
                                                    {{--                                                    <span class="input-group-btn">--}}
            {{--                                                          <button type="button" class="btn btn-default btn-number" disabled="disabled" data-type="minus" data-field="quant[1]">--}}
            {{--                                                              <span class="fa fa-minus"></span>--}}
            {{--                                                          </button>--}}
            {{--                                                      </span>--}}
                                                    {{--                                                    <input type="text" name="quant[1]" class="form-control input-number" value="1" min="1" max="10">--}}
                                                    {{--                                                    <span class="input-group-btn">--}}
            {{--                                                      <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="quant[1]">--}}
            {{--                                                          <span class="fa fa-plus"></span>--}}
            {{--                                                      </button>--}}
            {{--                                                  </span>--}}
                                                </div>
                                                <div class="cart-qty clearfix">Total Price</div>
                                                <div class="cart-price clearfix">
                                                    @if($course->free == 1)
                                                    {{trans('labels.backend.bundles.fields.free')}}
                                                    @else
                                                    {{$appCurrency['symbol'].' '.$course->price}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @endforeach
                            @endif
                            @if (isset($storeItems) && count($storeItems) > 0)
                                @foreach($storeItems as $item)
                                <div class="cartbox clearfix">
                                    <div class="cartbtn clearfix"><a href="{{route('cart.remove',['storeItem'=>$item])}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></div>
                                    <div class="row clearfix">
                                        <div class="col-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">
                                            <img src="{{asset('storage/uploads/'.$item->item_image)}}" alt="" class="img-full" />
                                        </div>
                                        <div class="col-12 col-sm-7 col-md-7 col-lg-7 col-xl-7">
                                            <div class="cart-title clearfix">{{$item->title}}</div>
                                            <div class="cart-price clearfix">
                                                <span> {{$appCurrency['symbol'].' '.$item->price}}</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3">
                                            <div class="cartblock clearfix">
                                                <div class="cart-qty clearfix">Quantity</div>
                                                <div class="input-group mtb-10">{{$item->quantity}}</div>
                                                <div class="cart-qty clearfix">Total Price</div>
                                                <div class="cart-price clearfix">
                                                    @if($item->free == 1)
                                                    {{trans('labels.backend.bundles.fields.free')}}
                                                    @else
                                                    {{$appCurrency['symbol'].' '.$item->price}}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        @else
                            <h6>@lang('labels.frontend.cart.empty_cart')</h6>
                        @endif
                </div>
                <!-- Cart side bar-->
                <!-- ============================ -->

                @if(count($courses) > 0 || count($storeItems) > 0)
                    <div class="purchase-list col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                        @include('frontend.cart.partials.order-stats-5')
                    </div>
                @else
                    <div class="purchase-list col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                        <div class="bg-f4f6f6 clearfix">
                            <div class="carttitle clearfix">@lang('labels.frontend.cart.order_detail')</div>
                            <ul class="cart-items clearfix">
                                <li><div class="carts-text font-bold clearfix">@lang('labels.backend.coupons.fields.total')
                                        <span>{{$appCurrency['symbol']}}0.00</span></div></li>
                            </ul>
                        </div>
                    </div>

                @endif

                <!-- End Cart side bar-->
                <!-- ============================ -->
            </div>

        </div>
    </section>
    <!-- End  of Checkout content
        ============================================= -->

@endsection

@push('after-scripts')
    @if(config('services.stripe.active') == 1)
        <script type="text/javascript" src="{{asset('js/stripe-form.js')}}"></script>
    @endif
    <script>
        $(document).ready(function () {
            $(document).on('click', 'input[type="radio"]:checked', function () {
                $('#accordion .check-out-form').addClass('disabled')
                $(this).closest('.payment-method').find('.check-out-form').removeClass('disabled')
            })

            $(document).on('click', '#applyCoupon', function () {
                var coupon = $('#coupon');
                if (!coupon.val() || (coupon.val() == "")) {
                    coupon.addClass('warning');
                    $('#coupon-error').html("<small>{{trans('labels.frontend.cart.empty_input')}}</small>").removeClass('d-none')
                    setTimeout(function () {
                        $('#coupon-error').empty().addClass('d-none')
                        coupon.removeClass('warning');

                    }, 5000);
                } else {
                    $('#coupon-error').empty().addClass('d-none')
                    $.ajax({
                        method: 'POST',
                        url: "{{route('cart.applyCoupon')}}",
                        data: {
                            _token: '{{csrf_token()}}',
                            coupon: coupon.val()
                        }
                    }).done(function (response) {
                        if (response.status === 'fail') {
                            coupon.addClass('warning');
                            $('#coupon-error').removeClass('d-none').html("<small>" + response.message + "</small>");
                            setTimeout(function () {
                                $('#coupon-error').empty().addClass('d-none');
                                coupon.removeClass('warning');

                            }, 5000);
                        } else {
                            $('.purchase-list').empty().html(response.html)
                            $('#applyCoupon').removeClass('btn-dark').addClass('btn-success')
                            $('#coupon-error').empty().addClass('d-none');
                            coupon.removeClass('warning');
                        }
                    });

                }
            });


            $(document).on('click','#removeCoupon',function () {
                $.ajax({
                    method: 'POST',
                    url: "{{route('cart.removeCoupon')}}",
                    data: {
                        _token: '{{csrf_token()}}',
                    }
                }).done(function (response) {
                    $('.purchase-list').empty().html(response.html)
                });
            })

        })
    </script>
@endpush