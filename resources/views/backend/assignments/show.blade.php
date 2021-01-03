@extends('backend.layouts.app')
@section('title', __('labels.backend.lessons.title').' | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">@lang('labels.backend.assignments.title')</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
{{--                        <tr>--}}
{{--                            <th>@lang('labels.backend.assignments.fields.course')</th>--}}
{{--                            <td>{{ $assignment->lesson->title or '' }}</td>--}}
{{--                        </tr>--}}
                        <tr>
                            <th>@lang('labels.backend.assignments.fields.title')</th>
                            <td>{{ $assignment->title }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.assignments.fields.slug')</th>
                            <td>{{ $assignment->slug }}</td>
                        </tr>
{{--                        <tr>--}}
{{--                            <th>@lang('labels.backend.assignments.fields.lesson_image')</th>--}}
{{--                            <td>@if($assignment->lesson_image)<a href="{{ asset('storage/uploads/' . $assignment->lesson_image) }}" target="_blank"><img--}}
{{--                                            src="{{ asset('storage/uploads/' . $assignment->lesson_image) }}" height="100px"/></a>@endif</td>--}}
{{--                        </tr>--}}
                        <tr>
                            <th>@lang('labels.backend.assignments.fields.summary')</th>
                            <td>{!! $assignment->summary !!}</td>
                        </tr>
{{--                        <tr>--}}
{{--                            <th>@lang('labels.backend.assignments.fields.full_text')</th>--}}
{{--                            <td>{!! $assignment->full_text !!}</td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <th>@lang('labels.backend.assignments.fields.position')</th>--}}
{{--                            <td>{{ $assignment->position }}</td>--}}
{{--                        </tr>--}}

{{--                        <tr>--}}
{{--                            <th>@lang('labels.backend.assignments.fields.media_pdf')</th>--}}
{{--                            <td>--}}
{{--                                @if($assignment->mediaPDF != null )--}}
{{--                                <p class="form-group">--}}
{{--                                    <a href="{{$assignment->mediaPDF->url}}" target="_blank">{{$assignment->mediaPDF->url}}</a>--}}
{{--                                </p>--}}
{{--                                @else--}}
{{--                                    <p>No PDF</p>--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <th>@lang('labels.backend.assignments.fields.media_audio')</th>--}}
{{--                            <td>--}}
{{--                                @if($assignment->mediaAudio != null )--}}
{{--                                <p class="form-group">--}}
{{--                                    <a href="{{$assignment->mediaAudio->url}}" target="_blank">{{$assignment->mediaAudio->url}}</a>--}}
{{--                                </p>--}}
{{--                                @else--}}
{{--                                    <p>No Audio</p>--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                        </tr>--}}

{{--                        <tr>--}}

{{--                            <th>@lang('labels.backend.assignments.fields.downloadable_files')</th>--}}
{{--                            <td>--}}
{{--                                @if(count($assignment->downloadableMedia) > 0 )--}}
{{--                                    @foreach($assignment->downloadableMedia as $media)--}}
{{--                                        <p class="form-group">--}}
{{--                                            <a href="{{ asset('storage/uploads/'.$media->name) }}"--}}
{{--                                               target="_blank">{{ $media->name }}--}}
{{--                                                ({{ $media->size }} KB)</a>--}}
{{--                                        </p>--}}
{{--                                    @endforeach--}}
{{--                                @else--}}
{{--                                    <p>No Files</p>--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <th>@lang('labels.backend.assignments.fields.media_video')</th>--}}
{{--                            <td>--}}
{{--                                @if($assignment->mediaVideo !=  null )--}}
{{--                                        <p class="form-group">--}}
{{--                                           <a href="{{$assignment->mediaVideo->url}}" target="_blank">{{$assignment->mediaVideo->url}}</a>--}}
{{--                                        </p>--}}
{{--                                @else--}}
{{--                                    <p>No Videos</p>--}}
{{--                                @endif--}}
{{--                            </td>--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <th>@lang('labels.backend.assignments.fields.published')</th>--}}
{{--                            <td>{{ Form::checkbox("published", 1, $assignment->published == 1 ? true : false, ["disabled"]) }}</td>--}}
{{--                        </tr>--}}
                    </table>
                </div>
            </div><!-- Nav tabs -->



            <a href="{{ route('admin.lessons.index') }}"
               class="btn btn-default border">@lang('strings.backend.general.app_back_to_list')</a>
        </div>
    </div>
@stop