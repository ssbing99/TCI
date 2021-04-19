@extends('backend.layouts.app')

@section('title','Zoom Invite | '.app_name())

@section('content')

    {!! Form::open(['method' => 'POST', 'route' => ['admin.course-user-zoom.store', ['course_id'=>$course->id]], 'files' => true,]) !!}
    {!! Form::hidden('model_id',0,['id'=>'lesson_id']) !!}
    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Create Zoom Schedule</h3>
        </div>
        <div class="card-body">
            @if (Auth::user()->isAdmin())
                <div class="row">
                    <div class="col-12 form-group">
                        {!! Form::label('students','Students', ['class' => 'control-label']) !!}
                        {!! Form::select('students[]', $students, old('students'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple', 'required' => true]) !!}
                    </div>
                </div>
            @endif

            @include('backend.course-user-zoom.form',['liveLessonSlot' => optional()])
            <div class="row">
                <div class="col-12  text-center form-group">
                    {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn  btn-danger']) !!}
                </div>
            </div>
        </div>
    </div>

    {!! Form::close() !!}

@stop
