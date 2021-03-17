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
    <header>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <h1>Enroll</h1>
                </div>
            </div>
        </div>
    </header>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of Checkout content
        ============================================= -->
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12 col-sm-8 col-md-8 col-lg-4 col-xl-4 col-xxl-4">
                    @if(session()->has('danger'))
                        <div class="alert alert-danger" role="alert">
                            {!! session('danger')  !!}
                        </div>
                    @endif
                </div>
            </div>
            <div class="row clearfix">

                <div class="col-12 col-sm-8 col-md-8 col-lg-8 col-xl-8 col-xxl-8">
                    <div class="page-title clearfix">{{$course->title}}</div>
                    <hr />
                    <p class="course-txt clearfix">
                        For your convenience, we offer payment by either PayPal or Stripe. If you have a PayPal account, and wish to use it to pay, select the PayPal option. If you wish to pay by credit or debit card, please select the Stripe option. Thank you!
                    </p>
                    <div class="card clearfix">
                        <div class="card-header">Course Details</div>
                        <div class="card-body">
                            <p class="course-txt clearfix">
                                <span>Course Start Date</span>
                                Today
                                <span>Price</span>
                                {{$appCurrency['symbol'].' '.$course->price}}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 col-xxl-4">
                    <div class="card clearfix">
                        <div class="card-header">Payment Method</div>
                        <div class="card-body">
                            <form method="post"
                                  action="{{route('cart.singleCheckoutSubmit')}}"
                                  role="form"
                                  data-cc-on-file="false"
                                  data-stripe-publishable-key="{{config('services.stripe.key')}}"
                                  id="payment-form">
                                @csrf
                                <div class="form-group">
                                    <label>Course Price</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="coursePrice" id="withoutSkype" value="withoutSkype" checked>
                                        <label class="form-check-label" for="withoutSkype"> {{$appCurrency['symbol'].' '.$course->price}}</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Payment Method</label>
                                    @if(config('paypal.active') == 1)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="paymentMethod" id="paypal" value="paypal">
                                        <label class="form-check-label" for="paypal">Paypal</label>
                                    </div>
                                    @endif
                                    @if(config('services.stripe.active') == 1)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="paymentMethod" id="stripe" value="stripe">
                                        <label class="form-check-label" for="stripe">Stripe</label>
                                    </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Enter your Coupon Code</label>
                                    <input type="text" name="coupon" id="coupon" class="form-control" placeholder="Enter Copoun Code" />
                                </div>

                                <div id="stripe-detail" style="display:none;">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="cc-name">@lang('labels.frontend.cart.name_on_card')</label>
                                            <input type="text" id="card-name" class="form-control required card-name" placeholder="">
                                            <small class="text-muted">@lang('labels.frontend.cart.name_on_card_placeholder')</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cc-number">@lang('labels.frontend.cart.card_number')</label>
                                            <input type="text" class="form-control required card-number" id="cc-number" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="cc-expiration">@lang('labels.frontend.cart.expiration_date')</label>
                                            <input type="text" class="form-control required card-expiry-month-year" id="cc-expiration" placeholder="MM/YY">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cc-cvv">@lang('labels.frontend.cart.cvv')</label>
                                            <input type="text" class="form-control card-cvc required" id="cc-cvv" placeholder="">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-12 error form-group d-none">
                                            <div class="alert-danger alert">
                                                @lang('labels.frontend.cart.stripe_error_message')
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="productId" value="{{$course->id}}"/>
                                <input type="hidden" name="isMentorship" value="true"/>
                                <input type="hidden" name="selected_teachers" value="{{$selected_teachers}}"/>
                                <button id="submitBtn" type="button" onclick="onSubmit(this)" class="btn btn-primary btn-lg">
                                    Enroll
                                </button>
{{--                                <input type="submit" name="submit" id="submit" value="ENROLL" class="btn btn-primary btn-lg" />--}}
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End  of Checkout content
        ============================================= -->

@endsection

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    @if(config('services.stripe.active') == 1)
        <script type="text/javascript" src="{{asset('js/stripe-form.js')}}"></script>
    @endif
    <script>

        function onSubmit(_this) {
            var form = $(_this.form);

            var paypalRadio = document.getElementById('paypal');
            var stripeRadio = document.getElementById('stripe');

            var submit = true;

            if(stripeRadio && stripeRadio.checked) {
                stripeRadio.click();
                var ccname= document.getElementById('card-name');
                var ccnumber= document.getElementById('cc-number');
                var ccexp= document.getElementById('cc-expiration');
                var cccvv= document.getElementById('cc-cvv');

                if(ccname.value == ''){
                    ccname.focus();
                    submit = false;
                }else if(ccnumber.value == ''){
                    ccnumber.focus();
                    submit = false;
                }else if(ccexp.value == ''){
                    ccexp.focus();
                    submit = false;
                }else if(cccvv.value == ''){
                    cccvv.focus();
                    submit = false;
                }
            }

            if(paypalRadio && paypalRadio.checked){
                paypalRadio.click();
            }
            var buttons = document.getElementById('submitBtn');
            // buttons.disabled = true;

            if(submit)
                form.submit();
            // }
        }

        $(document).ready(function () {
            //Clean cookies
            if(Cookies.get('withEnroll'))
                Cookies.remove('withEnroll');


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
            });

            $(document).on('change','input[name=paymentMethod]', function() {
                var paymentMethod = $('input[name=paymentMethod]:checked').val();
                switch (paymentMethod) {
                    case 'stripe':
                        $('#payment-form').attr('data-cc-on-file',false);
                        $('#payment-form').removeAttr('data-pay-type');
                        $('#stripe-detail').show();
                        // $('#paypal-detail').hide();
                        // $('#offline-detail').hide();
                        break;
                    case 'paypal':
                        $('#payment-form').removeAttr('data-cc-on-file');
                        $('#payment-form').attr('data-pay-type','paypal');
                        $('#stripe-detail').hide();
                        // $('#paypal-detail').show();
                    //     // $('#offline-detail').hide();
                        break;
                    // case 'offline':
                    //     $('#payment-form').removeAttr('data-cc-on-file');
                    //     $('#stripe-detail').hide();
                        // $('#paypal-detail').hide();
                        // $('#offline-detail').show();
                        // break;
                }
            });

        })
    </script>
@endpush