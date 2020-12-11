@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.cart.payment_status').' | '.app_name())

@push('after-styles')
    <style>
        input[type="radio"] {
            display: inline-block !important;
        }
    </style>
@endpush

@php

$savedAddress = Session::has('saved_address') ? json_decode(session('saved_address'), true) : null;
$courses = Session::has('courses') ? session('courses') : [];
$storeItems = Session::has('storeItems') ? session('storeItems'): [];
$total = session('total');
@endphp

@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    @if(config('theme_layout') == 5)
        <div class="banner custom-banner-bg">
            <div class="container">
                <div class="page-heading">
                    @lang('labels.frontend.cart.your_payment_status')
                </div>
            </div>
        </div>
    @else
    <section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style">
        <div class="blakish-overlay"></div>
        <div class="container">
            <div class="page-breadcrumb-content text-center">
                <div class="page-breadcrumb-title">
                    <h2 class="breadcrumb-head black bold">@lang('labels.frontend.cart.your_payment_status')</h2>
                </div>
            </div>
        </div>
    </section>
    @endif
    <!-- End of breadcrumb section
        ============================================= -->
    <section id="checkout" class="checkout-section">
        <div class="container">
            <div class="section-title mb45 headline text-center">


                @if(Session::has('success'))
                    <div class="alert alert-success" role="alert">
                        This is a success alert—check it out!
                    </div>
                @endif
                @if(Session::has('failure'))
                    <div class="alert alert-danger" role="alert">
                        This is a danger alert—check it out!
                    </div>
                    <h4><a href="{{route('cart.index')}}">@lang('labels.frontend.cart.go_back_to_cart')</a></h4>
                @endif
            </div>
        </div>
        @if(Session::has('saved_address'))
        <div class="container">
            <div class="row clearfix">
                <div class="col-md-8 order-md-1">
                    <form id="address-form">
                        <h4 class="mb-3">Billing address</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">First name</label>
                                <input type="text" class="form-control" readonly id="firstName" name="firstName" placeholder="First Name" value="{{ $savedAddress['firstName'] ?? '' }}" required="">
                                <div class="invalid-feedback"> Valid first name is required. </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName">Last name</label>
                                <input type="text" class="form-control" readonly id="lastName" name="lastName" placeholder="Last Name" value="{{ $savedAddress['lastName'] ?? '' }}" required="">
                                <div class="invalid-feedback"> Valid last name is required. </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email">Email <span class="text-muted">*</span></label>
                            <input type="email" class="form-control" readonly id="email" name="email" placeholder="you@example.com" value="{{ $savedAddress['email'] ?? '' }}" required="">
                            <div class="invalid-feedback"> Please enter a valid email address for shipping updates. </div>
                        </div>
                        <div class="mb-3">
                            <label for="email">Phone <span class="text-muted">*</span></label>
                            <input type="text" class="form-control" readonly id="phone" name="phone" placeholder="Phone No." value="{{ $savedAddress['phone'] ?? '' }}">
                            <div class="invalid-feedback"> Please enter a valid email address for shipping updates. </div>
                        </div>
                        <div class="mb-3">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" readonly id="address" name="address" placeholder="Address" value="{{ $savedAddress['address'] ?? '' }}" required="">
                            <div class="invalid-feedback"> Please enter your shipping address. </div>
                        </div>
                        <div class="mb-3">
                            <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control" readonly id="address2" name="address2" placeholder="Apartment or suite" value="{{ $savedAddress['address2'] ?? '' }}">
                        </div>
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="country">Country</label>
                                {!! Form::select('size', array('' => 'Choose...', 'United States' => 'United States'), $savedAddress['country'] ?? '', array('required' => '', 'disabled' => '', 'class' => 'custom-select d-block w-100', 'name' => 'country', 'id' => 'country')) !!}

                                <!--<select class="custom-select d-block w-100" id="country" name="country" required="">
                                    <option value="">Choose...</option>
                                    <option value="US">United States</option>
                                </select>-->
                                <div class="invalid-feedback"> Please select a valid country. </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="state">State</label>
                                {!! Form::select('size', array('' => 'Choose...', 'California' => 'California'), $savedAddress['state'] ?? '', array('required' => '', 'disabled' => '', 'class' => 'custom-select d-block w-100', 'name' => 'state', 'id' => 'state')) !!}
                                <!--<select class="custom-select d-block w-100" id="state" name="state" required="">
                                    <option value="">Choose...</option>
                                    <option>California</option>
                                </select>-->
                                <div class="invalid-feedback"> Please provide a valid state. </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="zip">Zip</label>
                                <input type="text" class="form-control" readonly id="zip" placeholder="" name="zip" required="" value="{{ $savedAddress['zip'] ?? '' }}">
                                <div class="invalid-feedback"> Zip code required. </div>
                            </div>
                        </div>
                        <hr class="mb-4">
                    </form>
                </div>

                <!--Start of Cart Summary-->
                <div class="col-md-4 order-md-2 mb-4">
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">@lang('labels.frontend.cart.your_cart')</span>
                        <span class="badge badge-theme badge-pill">{{ count($courses) + count($storeItems) }}</span>
                    </h4>
                    <ul class="list-group mb-3 sticky-top">
                        @if(count($courses) > 0 || count($storeItems) > 0)
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
                        @foreach($storeItems as $item)
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0">{{$item->title}}</h6>
                            </div>
                            <span class="text-muted"> {{$appCurrency['symbol'].''.$item->price}}</span>
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
        @endif
    </section>
@endsection