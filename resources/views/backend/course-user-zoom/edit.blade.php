@extends('backend.layouts.app')
@section('title', 'Zoom Invite | '.app_name())

@section('content')
    {!! Form::model($courseStudentZoom, ['method' => 'PUT', 'route' => ['admin.course-user-zoom.update', ['course_user_zoom' => $courseStudentZoom]], 'files' => true,]) !!}
    {!! Form::hidden('course_id',$course->id,['id'=>'course_id']) !!}
    {!! Form::hidden('course_user_zoom_id',$courseStudentZoom->id,['id'=>'course_user_zoom_id']) !!}
    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Edit Zoom Schedule</h3>
        </div>
        <div class="card-body">
            @if (Auth::user()->isAdmin())
                <div class="row">
                    <div class="col-12 form-group">
                        {!! Form::label('students','Students', ['class' => 'control-label']) !!}
                        {!! Form::select('students[]', $students, old('students') ? old('students') : $selected->toArray(), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple', 'required' => true]) !!}
                    </div>
                </div>
            @endif

            @include('backend.course-user-zoom.form')
            <div class="row">
                <div class="col-12  text-center form-group">
                    {!! Form::submit(trans('strings.backend.general.app_update'), ['class' => 'btn  btn-danger']) !!}
                </div>
            </div>
        </div>
    </div>

    {!! Form::close() !!}

@stop
