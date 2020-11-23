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

<!-- Start of course section
    ============================================= -->
<section>
    <div class="container">
        <div class="row clearfix">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                @if(session()->has('success'))
                <div class="alert alert-dismissable alert-success fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{session('success')}}
                </div>
                @endif
                <!--<div class="page-title clearfix">
                    <div class="row clearfix">
                        <div class="col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                            <label class="title">@lang('labels.frontend.course.sort_by')
                                <select id="sortBy" class="form-control" style="margin-left: 10px;">
                                    <option value="">@lang('labels.frontend.course.none')</option>
                                    <option value="popular">@lang('labels.frontend.course.popular')</option>
                                    <option value="trending">@lang('labels.frontend.course.trending')</option>
                                    <option value="featured">@lang('labels.frontend.course.featured')</option>
                                </select>
                            </label>
                        </div>
                    </div>
                </div>-->

                <div id="products" class="row mtb-15 clearfix">
                    @if($storeItems->count() > 0)
                        @foreach($storeItems as $item)
                        <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                            <!--<img src="@if($item->item_image != "") {{asset('storage/uploads/'.$item->item_image)}} @else {{asset('assets_new/images/course-img.jpg')}} @endif"  alt="" />
                            <div class="price">
                                {{$appCurrency['symbol'].' '.$item->price}}
                            </div>-->

                            <div class="storegrid clearfix">
                                <a href="{{ route('store.show', [$item->slug]) }}" >
                                <img src="@if($item->item_image != "") {{asset('storage/uploads/'.$item->item_image)}} @else {{asset('assets_new/images/course-img.jpg')}} @endif" alt="" />
                                <div class="overlay"></div>
                                </a>
                                @if($item->discount > 0)
                                <div class="tag">Discount</div>
                                @endif
                                <span>
                                    {{$appCurrency['symbol'].' '.$item->price}}
                                    @if(auth()->check())
                                    <form action="{{ route('cart.addToCart') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="storeItem_id" value="{{ $item->id }}"/>
                                        <input type="hidden" name="amount" value="{{ $item->price}}"/>
                                        <a href="#" onclick="$(this).closest('form').submit()" class="btn btn-theme btn-sm"><i class="fa fa-shopping-cart"></i>@lang('labels.frontend.course.add_to_cart')</a>
                                    </form>
                                    @else
                                        <a id="openLoginModal" data-target="#myModal" href="#" class="btn btn-theme btn-sm"><i class="fa fa-shopping-cart"></i>@lang('labels.frontend.course.add_to_cart')</a>
                                    @endif
                                </span>
                            </div>
                            <div class="bg-f4f6f6 clearfix">
                                <div class="storetitle clearfix">{{ $item->title }}</div>
                                <a href="{{ route('store.show', [$item->slug]) }}" class="btn btn-theme btn-sm">View Details</a>
                            </div>
                        </div>
                        @endforeach
                    @else
                    <h3>@lang('labels.general.no_data_available')</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- End of course section
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