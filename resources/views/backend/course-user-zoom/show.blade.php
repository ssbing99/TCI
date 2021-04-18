@extends('backend.layouts.app')
@section('title', 'Zoom Schedule | '.app_name())

@section('content')

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Zoom Schedule Details</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('labels.backend.live_lessons.fields.course')</th>
                            <td>{{ $courseStudentZoom->course->title }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.live_lesson_slots.fields.meeting_id')</th>
                            <td>{{ $courseStudentZoom->meeting_id }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.live_lesson_slots.fields.topic')</th>
                            <td>{{ $courseStudentZoom->topic }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.live_lesson_slots.fields.short_text')</th>
                            <td>{!! $courseStudentZoom->description !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.live_lesson_slots.fields.duration')</th>
                            <td>{!! $courseStudentZoom->duration !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.live_lesson_slots.fields.date')</th>
                            <td>{!! $courseStudentZoom->start_at->format('d-m-Y h:i:s A') !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.live_lesson_slots.start_url')</th>
                            <td>
                                <a href="{{ $courseStudentZoom->start_url }}" target="_blank" class="btn btn-info">
                                    @lang('labels.backend.live_lesson_slots.start_url')
                                </a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div><!-- Nav tabs -->

{{--            @if($courseStudentZoom->lessonSlotBookings->count())--}}
{{--            <h4> @lang('labels.backend.live_lesson_slots.slot_booked_student_list')</h4>--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-12">--}}
{{--                    <table class="table table-bordered">--}}
{{--                        <tr>--}}
{{--                            <th>@lang('labels.backend.live_lesson_slots.student_name')</th>--}}
{{--                            <th>@lang('labels.backend.live_lesson_slots.student_email')</th>--}}
{{--                        </tr>--}}
{{--                        @forelse($courseStudentZoom->lessonSlotBookings as $booking)--}}
{{--                        <tr>--}}
{{--                            <td>{{ $booking->user->name }}</td>--}}
{{--                            <td>{{ $booking->user->email }}</td>--}}
{{--                        </tr>--}}
{{--                        @empty--}}
{{--                        @endforelse--}}
{{--                    </table>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            @endif--}}

            <a href="{{ route('admin.course-user-zoom.index') }}"
               class="btn btn-default border">@lang('strings.backend.general.app_back_to_list')</a>
        </div>
    </div>
@stop
