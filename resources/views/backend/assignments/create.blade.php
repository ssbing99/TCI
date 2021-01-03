@extends('backend.layouts.app')
@section('title', __('labels.backend.lessons.title').' | '.app_name())

@push('after-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}">
    <style>
        .select2-container--default .select2-selection--single {
            height: 35px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 35px;
        }

        .bootstrap-tagsinput {
            width: 100% !important;
            display: inline-block;
        }

        .bootstrap-tagsinput .tag {
            line-height: 1;
            margin-right: 2px;
            background-color: #2f353a;
            color: white;
            padding: 3px;
            border-radius: 3px;
        }

    </style>

@endpush

@section('content')

    {!! Form::open(['method' => 'POST', 'route' => ['admin.assignments.store'], 'files' => true,]) !!}
    {!! Form::hidden('model_id',0,['id'=>'lesson_id']) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">@lang('labels.backend.assignments.create')</h3>
            <div class="float-right">
                <a href="{{ route('admin.lessons.index') }}"
                   class="btn btn-success">@lang('labels.backend.assignments.view')</a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
{{--                <div class="col-12 col-lg-6 form-group">--}}
{{--                    {!! Form::label('lesson_id', trans('labels.backend.assignments.fields.title'), ['class' => 'control-label']) !!}--}}
{{--                    {!! Form::select('lesson_id', $lessons,  (request('lesson_id')) ? request('lesson_id') : old('lesson_id'), ['class' => 'form-control select2']) !!}--}}
{{--                </div>--}}
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('title', trans('labels.backend.assignments.fields.title').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.assignments.fields.title'), 'required' => '']) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('slug',trans('labels.backend.assignments.fields.slug'), ['class' => 'control-label']) !!}
                    {!! Form::text('slug', old('slug'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.assignments.slug_placeholder')]) !!}

                </div>
{{--                <div class="col-12 col-lg-6 form-group">--}}
{{--                    {!! Form::label('lesson_image', trans('labels.backend.assignments.fields.lesson_image').' '.trans('labels.backend.assignments.max_file_size'), ['class' => 'control-label']) !!}--}}
{{--                    {!! Form::file('lesson_image', ['class' => 'form-control' , 'accept' => 'image/jpeg,image/gif,image/png']) !!}--}}
{{--                    {!! Form::hidden('lesson_image_max_size', 8) !!}--}}
{{--                    {!! Form::hidden('lesson_image_max_width', 4000) !!}--}}
{{--                    {!! Form::hidden('lesson_image_max_height', 4000) !!}--}}

{{--                </div>--}}
            </div>

            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('summary', trans('labels.backend.assignments.fields.summary'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('summary', old('short_text'), ['class' => 'form-control editor', 'placeholder' => trans('labels.backend.assignments.summary'), 'id' => 'editorS']) !!}

                </div>
            </div>
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('full_text', trans('labels.backend.assignments.fields.full_text'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('full_text', old('full_text'), ['class' => 'form-control editor', 'placeholder' => '','id' => 'editor']) !!}

                </div>
            </div>
{{--            <div class="row">--}}
{{--                <div class="col-12 form-group">--}}
{{--                    {!! Form::label('downloadable_files', trans('labels.backend.assignments.fields.downloadable_files').' '.trans('labels.backend.assignments.max_file_size'), ['class' => 'control-label']) !!}--}}
{{--                    {!! Form::file('downloadable_files[]', [--}}
{{--                        'multiple',--}}
{{--                        'class' => 'form-control file-upload',--}}
{{--                        'id' => 'downloadable_files',--}}
{{--                        'accept' => "image/jpeg,image/gif,image/png,application/msword,audio/mpeg,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.ms-powerpoint,application/pdf,video/mp4"--}}
{{--                        ]) !!}--}}
{{--                    <div class="photo-block">--}}
{{--                        <div class="files-list"></div>--}}
{{--                    </div>--}}

{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="row">--}}
{{--                <div class="col-12 form-group">--}}
{{--                    {!! Form::label('pdf_files', trans('labels.backend.assignments.fields.add_pdf'), ['class' => 'control-label']) !!}--}}
{{--                    {!! Form::file('add_pdf', [--}}
{{--                        'class' => 'form-control file-upload',--}}
{{--                         'id' => 'add_pdf',--}}
{{--                        'accept' => "application/pdf"--}}

{{--                        ]) !!}--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="row">--}}
{{--                <div class="col-12 form-group">--}}
{{--                    {!! Form::label('audio_files', trans('labels.backend.assignments.fields.add_audio'), ['class' => 'control-label']) !!}--}}
{{--                    {!! Form::file('add_audio', [--}}
{{--                        'class' => 'form-control file-upload',--}}
{{--                         'id' => 'add_audio',--}}
{{--                        'accept' => "audio/mpeg3"--}}

{{--                        ]) !!}--}}
{{--                </div>--}}
{{--            </div>--}}


{{--            <div class="row">--}}
{{--                <div class="col-md-12 form-group">--}}
{{--                    {!! Form::label('add_video', trans('labels.backend.assignments.fields.add_video'), ['class' => 'control-label']) !!}--}}

{{--                    {!! Form::select('media_type', ['youtube' => 'Youtube','vimeo' => 'Vimeo','upload' => 'Upload','embed' => 'Embed'],null,['class' => 'form-control', 'placeholder' => 'Select One','id'=>'media_type' ]) !!}--}}

{{--                    {!! Form::text('video', old('video'), ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.assignments.enter_video_url'),'id'=>'video'  ]) !!}--}}


{{--                    {!! Form::file('video_file', ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.assignments.enter_video_url'),'id'=>'video_file'  ]) !!}--}}

{{--                    @lang('labels.backend.assignments.video_guide')--}}

{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="row">--}}

{{--                <div class="col-12 col-lg-3 form-group">--}}
{{--                    <div class="checkbox">--}}
{{--                        {!! Form::hidden('published', 0) !!}--}}
{{--                        {!! Form::checkbox('published', 1, false, []) !!}--}}
{{--                        {!! Form::label('published', trans('labels.backend.assignments.fields.published'), ['class' => 'checkbox control-label font-weight-bold']) !!}--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-12  text-left form-group">--}}
                    {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn  btn-danger']) !!}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>

    <input type="hidden" name="published" value="1"/>
    <input type="hidden" name="lesson_id" value="{{request('lesson_id')}}"/>
    {!! Form::close() !!}



@stop

@push('after-scripts')
    <script src="{{asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.js')}}"></script>
    <script type="text/javascript" src="{{asset('/vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{asset('/vendor/unisharp/laravel-ckeditor/adapters/jquery.js')}}"></script>
    <script src="{{asset('/vendor/laravel-filemanager/js/lfm.js')}}"></script>
    <script>
        $('.editor').each(function () {

            CKEDITOR.replace($(this).attr('id'), {
                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()}}',
                extraPlugins: 'smiley,lineutils,widget,codesnippet,prism,flash',
            });

        });

        $('.editorS').each(function () {

            CKEDITOR.replace($(this).attr('id'), {
                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()}}',
                extraPlugins: 'smiley,lineutils,widget,codesnippet,prism,flash',
            });

        });

        var uploadField = $('input[type="file"]');

        $(document).on('change', 'input[name="lesson_image"]', function () {
            var $this = $(this);
            $(this.files).each(function (key, value) {
                if (value.size > 5000000) {
                    alert('"' + value.name + '"' + 'exceeds limit of maximum file upload size')
                    $this.val("");
                }
            })
        });

        $(document).on('change', '#media_type', function () {
            if ($(this).val()) {
                if ($(this).val() != 'upload') {
                    $('#video').removeClass('d-none').attr('required', true)
                    $('#video_file').addClass('d-none').attr('required', false)
                } else if ($(this).val() == 'upload') {
                    $('#video').addClass('d-none').attr('required', false)
                    $('#video_file').removeClass('d-none').attr('required', true)
                }
            } else {
                $('#video_file').addClass('d-none').attr('required', false)
                $('#video').addClass('d-none').attr('required', false)
            }
        })

    </script>

@endpush