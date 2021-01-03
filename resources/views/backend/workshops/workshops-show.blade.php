@extends('backend.layouts.app')
@section('title', __('labels.backend.courses.title').' | '.app_name())

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
            <h3 class="page-title mb-0">@lang('labels.backend.workshops.title')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('labels.backend.workshops.fields.teachers')</th>
                            <td>
                                @foreach ($workshop->teachers as $singleTeachers)
                                    <span class="label label-info label-many">{{ $singleTeachers->name }}</span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.workshops.fields.title')</th>
                            <td>
                                @if($workshop->published == 1)
                                    <a target="_blank"
                                       href="{{ route('workshops.show', [$workshop->slug]) }}">{{ $workshop->title }}</a>
                                @else
                                    {{ $workshop->title }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.workshops.fields.slug')</th>
                            <td>{{ $workshop->slug }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.workshops.fields.description')</th>
                            <td>{!! $workshop->description !!}</td>
                        </tr>
                        <tr>
                            <th>Enrolment Details</th>
                            <td>{!! $workshop->enrolment_details !!}</td>
                        </tr>
                        <tr>
                            <th>Upcoming Workshop</th>
                            <td>{!! $workshop->upcoming_workshop !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.workshops.fields.price')</th>
                            <td>{{ ($workshop->free == 1) ? trans('labels.backend.workshops.fields.free') : $workshop->price.' '.$appCurrency['symbol'] }}</td>
                        </tr>
                        <tr>
                            <th>Deposit</th>
                            <td>{{ ($workshop->free == 1) ? trans('labels.backend.workshops.fields.free') : $workshop->deposit.' '.$appCurrency['symbol'] }}</td>
                        </tr>
                        <tr>
                            <th>Balance</th>
                            <td>{{ ($workshop->free == 1) ? trans('labels.backend.workshops.fields.free') : $workshop->balance.' '.$appCurrency['symbol'] }}</td>
                        </tr>
                        <tr>
                            <th>Single Supplement</th>
                            <td>{{ ($workshop->free == 1) ? trans('labels.backend.workshops.fields.free') : $workshop->single_supplement.' '.$appCurrency['symbol'] }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.workshops.fields.workshop_image')</th>
                            <td>@if($workshop->workshop_image)<a
                                        href="{{ asset('storage/uploads/' . $workshop->workshop_image) }}"
                                        target="_blank"><img
                                            src="{{ asset('storage/uploads/' . $workshop->workshop_image) }}"
                                            height="50px"/></a>@endif</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.lessons.fields.media_video')</th>
                            <td>
                                @if($workshop->mediaVideo !=  null )
                                    <p class="form-group mb-0">
                                        <a href="{{$workshop->mediaVideo->url}}"
                                           target="_blank">{{$workshop->mediaVideo->url}}</a>
                                    </p>
                                @else
                                    <p>No Videos</p>
                                @endif
                            </td>
                        </tr>
                        {{--                        <tr>--}}
                        {{--                            <th>@lang('labels.backend.workshops.fields.start_date')</th>--}}
                        {{--                            <td>{{ $workshop->start_date }}</td>--}}
                        {{--                        </tr>--}}
                        <tr>
                            <th>@lang('labels.backend.workshops.fields.published')</th>
                            <td>{{ Form::checkbox("published", 1, $workshop->published == 1 ? true : false, ["disabled"]) }}</td>
                        </tr>

                        <tr>
                            <th>@lang('labels.backend.workshops.fields.meta_title')</th>
                            <td>{{ $workshop->meta_title }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.workshops.fields.meta_description')</th>
                            <td>{{ $workshop->meta_description }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.workshops.fields.meta_keywords')</th>
                            <td>{{ $workshop->meta_keywords }}</td>
                        </tr>
                    </table>
                </div>
            </div><!-- Nav tabs -->

        </div>
    </div>
@stop

