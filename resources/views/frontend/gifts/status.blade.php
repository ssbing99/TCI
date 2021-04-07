@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.cart.payment_status').' | '.app_name())

@push('after-styles')
    <style>
        input[type="radio"] {
            display: inline-block !important;
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
                    <h1>Summary</h1>
                </div>
            </div>
        </div>
    </header>

    <section>
        <div class="container">
            <div class="section-title mb45 headline text-center">

                @if(Session::has('success'))
                    <div class="alert alert-success" role="alert">
                        Your payment is success.
                    </div>
                    <h4><a href="{{route('workshops.all')}}" class="btn btn-primary">Back</a></h4>
                @endif
                @if(Session::has('failure'))
                    <div class="alert alert-danger" role="alert">
                        Payment failed. Please try again later.
                    </div>
                    <h4><a href="{{route('workshops.all')}}" class="btn btn-primary">Back</a></h4>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script>

        $(document).ready(function () {
            //Clean cookies
            if(Cookies.get('withWorkshop'))
                Cookies.remove('withWorkshop');

        })
    </script>
@endpush