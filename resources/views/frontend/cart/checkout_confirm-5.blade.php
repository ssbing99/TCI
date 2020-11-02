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

        .custom-radio:not(:first-child) {
            margin-left:3rem!important
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
                @lang('labels.frontend.cart.checkout')
            </div>
        </div>
    </div>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of Checkout content
        ============================================= -->
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-8 order-md-1">
                    <form id="address-form">
                        <h4 class="mb-3">Billing address</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">First name</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" value="{{ $savedAddress['firstName'] ?? '' }}" required="">
                                <div class="invalid-feedback"> Valid first name is required. </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName">Last name</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" value="{{ $savedAddress['lastName'] ?? '' }}" required="">
                                <div class="invalid-feedback"> Valid last name is required. </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email">Email <span class="text-muted">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" value="{{ $savedAddress['email'] ?? '' }}" required="">
                            <div class="invalid-feedback"> Please enter a valid email address for shipping updates. </div>
                        </div>
                        <div class="mb-3">
                            <label for="email">Phone <span class="text-muted">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone No." value="{{ $savedAddress['phone'] ?? '' }}" required="">
                            <div class="invalid-feedback"> Please enter a valid email address for shipping updates. </div>
                        </div>
                        <div class="mb-3">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="{{ $savedAddress['address'] ?? '' }}" required="">
                            <div class="invalid-feedback"> Please enter your shipping address. </div>
                        </div>
                        <div class="mb-3">
                            <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control" id="address2" name="address2" placeholder="Apartment or suite" value="{{ $savedAddress['address2'] ?? '' }}">
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="country">Country</label>
                                {!! Form::select('size', array('' => 'Choose...', 'United States' => 'United States'), $savedAddress['country'] ?? '', array('required' => '', 'class' => 'custom-select d-block w-100', 'name' => 'country', 'id' => 'country')) !!}

                                <!--<select class="custom-select d-block w-100" id="country" name="country" required="">
                                    <option value="">Choose...</option>
                                    <option value="US">United States</option>
                                </select>-->
                                <div class="invalid-feedback"> Please select a valid country. </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="state">State</label>
                                {!! Form::select('size', array('' => 'Choose...', 'California' => 'California'), $savedAddress['state'] ?? '', array('required' => '', 'class' => 'custom-select d-block w-100', 'name' => 'state', 'id' => 'state')) !!}
                                <!--<select class="custom-select d-block w-100" id="state" name="state" required="">
                                    <option value="">Choose...</option>
                                    <option>California</option>
                                </select>-->
                                <div class="invalid-feedback"> Please provide a valid state. </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="zip">Zip</label>
                                <input type="text" class="form-control" id="zip" placeholder="" name="zip" required="" value="{{ $savedAddress['zip'] ?? '' }}">
                                <div class="invalid-feedback"> Zip code required. </div>
                            </div>
                        </div>
                        <hr class="mb-4">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="same-address">
                            <label class="custom-control-label" for="same-address">Shipping address is the same as my billing address</label>
                        </div>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="save-info" name="saveInfo" value="true" {{ $useSavedAddressFlag ? 'checked' : ''}}>
                            <label class="custom-control-label" for="save-info">Save this information for next time</label>
                        </div>
                    </form>

                    <hr class="mb-4">

                    <!--Start of Payment Options-->
                    <h4 class="mb-3">@lang('labels.frontend.cart.order_payment')</h4>
                    @if(count($courses) > 0)
                        @if((config('services.stripe.active') == 0) && (config('paypal.active') == 0) && (config('payment_offline_active') == 0))
                            <div class="order-payment">
                                <div class="section-title-2 headline text-left">
                                    <label>@lang('labels.frontend.cart.no_payment_method')</label>
                                </div>
                            </div>
                        @else
                            <div class="d-block my-3">
                                @if(config('services.stripe.active') == 1)
                                <div class="custom-control custom-radio">
                                    <input id="stripe" name="paymentMethod" value="stripe" type="radio" class="custom-control-input" required="">
                                    <label class="custom-control-label" for="stripe">@lang('labels.frontend.cart.payment_cards')</label>
                                </div>
                                @endif

                                @if(config('paypal.active') == 1)
                                <div class="custom-control custom-radio">
                                    <input id="paypal" name="paymentMethod" value="paypal" type="radio" class="custom-control-input" required="">
                                    <label class="custom-control-label" for="paypal">@lang('labels.frontend.cart.paypal')</label>
                                </div>
                                @endif

                                @if(config('payment_offline_active') == 1)
                                <div class="custom-control custom-radio">
                                    <input id="offline" name="paymentMethod" value="offline" type="radio" class="custom-control-input" required="">
                                    <label class="custom-control-label" for="offline">@lang('labels.frontend.cart.offline_payment')</label>
                                </div>
                                @endif
                            </div>
                            <div id="stripe-detail" style="display: none">
                                <form accept-charset="UTF-8"
                                      class="require-validation"
                                      action="{{route('cart.stripe.payment')}}" data-cc-on-file="false"
                                      data-stripe-publishable-key="{{config('services.stripe.key')}}"
                                      id="payment-form"
                                      method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="cc-name">@lang('labels.frontend.cart.name_on_card')</label>
                                            <input type="text" class="form-control required card-name" placeholder="">
                                            <small class="text-muted">@lang('labels.frontend.cart.name_on_card_placeholder')</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="cc-number">@lang('labels.frontend.cart.card_number')</label>
                                            <input type="text" class="form-control required card-number" id="cc-number" placeholder="">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label for="cc-expiration">@lang('labels.frontend.cart.expiration_date')</label>
                                            <input type="text" class="form-control required card-expiry-month-year" id="cc-expiration" placeholder="MM/YY">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="cc-cvv">@lang('labels.frontend.cart.cvv')</label>
                                            <input type="text" class="form-control card-cvc required" id="cc-cvv" placeholder="">
                                        </div>
                                    </div>
                                    <hr class="mb-4">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block mb-30">
                                        @lang('labels.frontend.cart.pay_now')
                                    </button>

                                    <div class="row mt-3">
                                        <div class="col-12 error form-group d-none">
                                            <div class="alert-danger alert">
                                                @lang('labels.frontend.cart.stripe_error_message')
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div id="paypal-detail" style="display: none">
                                <form method="post" id="payment-form" class="paypal" action="{{route('cart.paypal.payment')}}">
                                    {{ csrf_field() }}
                                    <p> @lang('labels.frontend.cart.pay_securely_paypal')</p>

                                    <hr class="mb-4">
                                    <button type="button" onclick="onSubmit(this)" class="btn btn-primary btn-lg btn-block mb-30">
                                        @lang('labels.frontend.cart.pay_now')
                                    </button>
                                </form>
                            </div>
                            <div id="offline-detail" style="display: none">
                                <form method="post" action="{{route('cart.offline.payment')}}">
                                    @csrf
                                    <p> @lang('labels.frontend.cart.offline_payment_note')</p>
                                    <p>{{ config('payment_offline_instruction')  }}</p>
                                    <hr class="mb-4">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block mb-30">
                                        @lang('labels.frontend.cart.request_assistance')
                                    </button>
                                </form>
                            </div>

                        @endif
                    @endif


                        <!--End of Payment Options-->
                </div>

                <!--Start of Cart Summary-->
                <div class="col-md-4 order-md-2 mb-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">@lang('labels.frontend.cart.your_cart')</span>
                    <span class="badge badge-theme badge-pill">{{ count($courses) }}</span>
                </h4>
                <ul class="list-group mb-3 sticky-top">
                    @if(count($courses) > 0)
                        @foreach($courses as $course)
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0">{{$course->title}}</h6>
                                <small class="text-muted">{{$course->category->name}}</small>
                            </div>
                            @if($course->free == 1)
                                <span class="text-muted">{{trans('labels.backend.bundles.fields.free')}}</span>
                            @else
                                <span class="text-muted"> {{$appCurrency['symbol'].''.$course->price}}</span>
                            @endif
                        </li>
                        @endforeach

                        @if(isset($total))
                        <li class="list-group-item d-flex justify-content-between">
                            <span>@lang('labels.backend.coupons.fields.total')</span>
                            <strong>{{$appCurrency['symbol'].' '.$total}}</strong>
                        </li>
                        @endif

                    @else
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0">@lang('labels.frontend.cart.empty_cart')</h6>
                            </div>
                        </li>
                    @endif


                </ul>
            </div>
                <!--End of Cart Summary-->
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

    function onSubmit(_this) {
        var form = $(_this.form);
        if (document.getElementById('address-form').reportValidity()) {
            var addressFormInputs = $('#address-form').find(':input');
            addressFormInputs.each(function(idx, item) {
                console.log(item);
                if ($(item).is(':checkbox')) {
                    if ($(item).is(':checked'))  {
                        var input = $("<input>")
                            .attr("type", "hidden")
                            .attr("name", item.name).val(item.value);
                        form.append(input);
                    }
                } else {
                    var input = $("<input>")
                        .attr("type", "hidden")
                        .attr("name", item.name).val(item.value);
                    form.append(input);
                }
            });
            form.submit();
        }
    }

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
        });

        $(document).on('change','input[name=paymentMethod]', function() {
            var paymentMethod = $('input[name=paymentMethod]:checked').val();
            switch (paymentMethod) {
                case 'stripe':
                    $('#stripe-detail').show();
                    $('#paypal-detail').hide();
                    $('#offline-detail').hide();
                    break;
                case 'paypal':
                    $('#stripe-detail').hide();
                    $('#paypal-detail').show();
                    $('#offline-detail').hide();
                    break;
                case 'offline':
                    $('#stripe-detail').hide();
                    $('#paypal-detail').hide();
                    $('#offline-detail').show();
                    break;
            }
        });

    })
</script>
@endpush