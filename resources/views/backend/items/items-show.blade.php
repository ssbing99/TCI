@extends('backend.layouts.app')
@section('title', __('labels.backend.items.title').' | '.app_name())

@push('after-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/amigo-sorter/css/theme-default.css')}}">
    <style>
        ul.sorter > span {
            display: inline-block;
            width: 100%;
            height: 100%;
            background: #f5f5f5;
            color: #333333;
            border: 1px solid #cccccc;
            border-radius: 6px;
            padding: 0px;
        }

        ul.sorter li > span .title {
            padding-left: 15px;
            width: 70%;
        }

        ul.sorter li > span .btn {
            width: 20%;
        }

        @media screen and (max-width: 768px) {

            ul.sorter li > span .btn {
                width: 30%;
            }

            ul.sorter li > span .title {
                padding-left: 15px;
                width: 70%;
                float: left;
                margin: 0 !important;
            }

        }


    </style>
@endpush

@section('content')

    <div class="card">

        <div class="card-header">
            <h3 class="page-title mb-0">@lang('labels.backend.items.title')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('labels.backend.items.fields.title')</th>
                            <td>
                                @if($item->published == 1)
                                    <a target="_blank"
                                       href="{{ route('items.show', [$item->slug]) }}">{{ $item->title }}</a>
                                @else
                                    {{ $item->title }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.items.fields.slug')</th>
                            <td>{{ $item->slug }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.items.fields.description')</th>
                            <td>{!! $item->description !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.items.fields.price')</th>
                            <td>{{ ($item->free == 1) ? trans('labels.backend.items.fields.free') : $item->price.' '.$appCurrency['symbol'] }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.items.fields.discount')</th>
                            <td>{!! $item->discount !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.items.fields.discount_type')</th>
                            <td>{!! $item->discount_type !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.items.fields.item_image')</th>
                            <td>@if($item->item_image)<a
                                        href="{{ asset('storage/uploads/' . $item->item_image) }}"
                                        target="_blank"><img
                                            src="{{ asset('storage/uploads/' . $item->item_image) }}"
                                            height="50px"/></a>@endif</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.items.fields.stock_count')</th>
                            <td>{{ $item->stock_count }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.items.fields.published')</th>
                            <td>{{ Form::checkbox("published", 1, $item->published == 1 ? true : false, ["disabled"]) }}</td>
                        </tr>

                        <tr>
                            <th>@lang('labels.backend.items.fields.meta_title')</th>
                            <td>{{ $item->meta_title }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.items.fields.meta_description')</th>
                            <td>{{ $item->meta_description }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.items.fields.meta_keywords')</th>
                            <td>{{ $item->meta_keywords }}</td>
                        </tr>
                    </table>
                </div>
            </div><!-- Nav tabs -->

        </div>
    </div>
@stop
