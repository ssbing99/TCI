@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )

@section('content')

    <!-- Start of breadcrumb section
            ============================================= -->
    <div class="banner custom-banner-bg">
        <div class="container">
            <div class="page-heading">
                <span>@lang('labels.frontend.store.store')</span>
            </div>
        </div>
    </div>
    <!-- End of breadcrumb section
        ============================================= -->

<!-- Start of store section
    ============================================= -->
    <section>
        <div class="container">
            <div class="row mtb-15 clearfix">
                @if(session()->has('success'))
                    <div class="col-12">
                        <div class="alert alert-dismissable alert-success fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{session('success')}}
                        </div>
                    </div>
                @endif
                <div class="col-12 col-sm-5 col-md-4 col-lg-4 col-xl-4">
                    @if($item->item_image != "")
                        <img src="{{asset('storage/uploads/'.$item->item_image)}}"
                             alt="" class="img-full">
                    @endif
                </div>
                <div class="col-12 col-sm-7 col-md-8 col-lg-8 col-xl-8">
                    <div class="product-title clearfix">{{$item->title}}</div>
                    <div class="product-price clearfix">{{$appCurrency['symbol'].' '.$item->price}}</div>
                    @if(auth()->check() && (auth()->user()->hasRole('student')) )
                    <form action="{{ route('cart.addToCart') }}" method="POST">
                        @csrf
                        <label>@lang('labels.backend.items.fields.quantity') <input type="number" name="quantity" id="quantity" value="1" style="width: 60px;" /></label><br />
                        <input type="hidden" name="storeItem_id" value="{{ $item->id }}"/>
                        <input type="hidden" name="amount" value="{{ $item->price}}"/>
                        <a href="#" onclick="$(this).closest('form').submit()" class="btn btn-theme btn-sm"><i class="fa fa-shopping-cart"></i>@lang('labels.frontend.course.add_to_cart')</a>
                    </form>
{{--                    <a href="cart.html" class="btn btn-theme btn-md mtb-15">ADD TO CART</a>--}}
{{--                    <div class="product-category clearfix">Categroy : <span>Online Course</span></div>--}}
                    @elseif(!auth()->check())
                        <a id="openLoginModal" data-target="#myModal" href="#" class="btn btn-theme btn-sm"><i class="fa fa-shopping-cart"></i>@lang('labels.frontend.course.add_to_cart')</a>
                    @endif
                </div>
            </div>
            <div class="row mtb-15 clearfix">
                <div class="col-12">
                    <div class="product-head clearfix">Description</div>
                    <p class="post-content clearfix">{!! $item->description !!}</p>
                </div>
            </div>
        </div>
    </section>

<!-- End of store section
    ============================================= -->



@endsection

@push('after-scripts')
    <script>
        $(document).ready(function () {
            $(document).on('change', '#sortBy', function () {

                if ($(this).val() != "") {
                    location.href = '{{url()->current()}}?type=' + $(this).val();
                } else {
                    location.href = '{{url()->current()}}';
                }
            });

            @if(request('type') != "")
            $('#sortBy').find('option[value="' + "{{request('type')}}" + '"]').attr('selected', true);
            @endif
        });

    </script>
@endpush