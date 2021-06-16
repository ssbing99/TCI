@extends('backend.layouts.app')
@section('title', __('labels.backend.assignments.title').' | '.app_name())

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
    {!! Form::model($assignment, ['method' => 'PUT', 'route' => ['admin.assignments.update', $assignment->id], 'files' => true,]) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">@lang('labels.backend.assignments.edit')</h3>
            <div class="float-right">
                <a href="{{ route('admin.assignments.index') }}"
                   class="btn btn-success">@lang('labels.backend.assignments.view')</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
{{--                <div class="col-12 col-lg-6 form-group">--}}
{{--                    {!! Form::label('course_id', trans('labels.backend.assignments.fields.course'), ['class' => 'control-label']) !!}--}}
{{--                    {!! Form::select('course_id', $courses, old('course_id'), ['class' => 'form-control select2']) !!}--}}
{{--                </div>--}}
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('title', trans('labels.backend.assignments.fields.title').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.assignments.fields.title'), 'required' => '']) !!}

                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('slug', trans('labels.backend.assignments.fields.slug'), ['class' => 'control-label']) !!}
                    {!! Form::text('slug', old('slug'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.assignments.slug_placeholder')]) !!}
                </div>
{{--                @if ($assignment->lesson_image)--}}

{{--                    <div class="col-12 col-lg-5 form-group">--}}

{{--                        {!! Form::label('lesson_image', trans('labels.backend.assignments.fields.lesson_image').' '.trans('labels.backend.assignments.max_file_size'), ['class' => 'control-label']) !!}--}}
{{--                        {!! Form::file('lesson_image', ['class' => 'form-control', 'accept' => 'image/jpeg,image/gif,image/png', 'style' => 'margin-top: 4px;']) !!}--}}
{{--                        {!! Form::hidden('lesson_image_max_size', 8) !!}--}}
{{--                        {!! Form::hidden('lesson_image_max_width', 4000) !!}--}}
{{--                        {!! Form::hidden('lesson_image_max_height', 4000) !!}--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-1 col-12 form-group">--}}
{{--                        <a href="{{ asset('uploads/'.$assignment->lesson_image) }}" target="_blank"><img--}}
{{--                                    src="{{ asset('uploads/'.$assignment->lesson_image) }}" height="65px"--}}
{{--                                    width="65px"></a>--}}
{{--                    </div>--}}
{{--                @else--}}
{{--                    <div class="col-12 col-lg-6 form-group">--}}

{{--                        {!! Form::label('lesson_image', trans('labels.backend.assignments.fields.lesson_image').' '.trans('labels.backend.assignments.max_file_size'), ['class' => 'control-label']) !!}--}}
{{--                        {!! Form::file('lesson_image', ['class' => 'form-control']) !!}--}}
{{--                        {!! Form::hidden('lesson_image_max_size', 8) !!}--}}
{{--                        {!! Form::hidden('lesson_image_max_width', 4000) !!}--}}
{{--                        {!! Form::hidden('lesson_image_max_height', 4000) !!}--}}
{{--                    </div>--}}
{{--                @endif--}}

            </div>

            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('summary', trans('labels.backend.assignments.fields.summary'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('summary', old('short_text'), ['class' => 'form-control editor', 'placeholder' => trans('labels.backend.assignments.fields.summary'),'id' => 'editorS']) !!}
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
{{--                         'id' => 'downloadable_files',--}}
{{--                        'accept' => "image/jpeg,image/gif,image/png,application/msword,audio/mpeg,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.ms-powerpoint,application/pdf,video/mp4"--}}

{{--                        ]) !!}--}}
{{--                    <div class="photo-block mt-3">--}}
{{--                        <div class="files-list">--}}
{{--                            @if(count($assignment->downloadableMedia) > 0)--}}
{{--                                @foreach($assignment->downloadableMedia as $media)--}}
{{--                                    <p class="form-group">--}}
{{--                                        <a href="{{ asset('storage/uploads/'.$media->name) }}"--}}
{{--                                           target="_blank">{{ $media->name }}--}}
{{--                                            ({{ $media->size }} KB)</a>--}}
{{--                                        <a href="#" data-media-id="{{$media->id}}"--}}
{{--                                           class="btn btn-xs btn-danger delete remove-file">@lang('labels.backend.assignments.remove')</a>--}}
{{--                                    </p>--}}
{{--                                @endforeach--}}
{{--                            @endif--}}
{{--                        </div>--}}
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
{{--                    <div class="photo-block mt-3">--}}
{{--                        <div class="files-list">--}}
{{--                            @if($assignment->mediaPDF)--}}
{{--                                <p class="form-group">--}}
{{--                                    <a href="{{ asset('storage/uploads/'.$assignment->mediaPDF->name) }}"--}}
{{--                                       target="_blank">{{ $assignment->mediaPDF->name }}--}}
{{--                                        ({{ $assignment->mediaPDF->size }} KB)</a>--}}
{{--                                    <a href="#" data-media-id="{{$assignment->mediaPDF->id}}"--}}
{{--                                       class="btn btn-xs btn-danger delete remove-file">@lang('labels.backend.assignments.remove')</a>--}}
{{--                                    <iframe src="{{asset('storage/uploads/'.$assignment->mediaPDF->name)}}" width="100%" height="500px">--}}
{{--                                    </iframe>--}}
{{--                                </p>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="row">--}}
{{--                <div class="col-12 form-group">--}}
{{--                    {!! Form::label('pdf_files', trans('labels.backend.assignments.fields.add_audio'), ['class' => 'control-label']) !!}--}}
{{--                    {!! Form::file('add_audio', [--}}
{{--                        'class' => 'form-control file-upload',--}}
{{--                         'id' => 'add_audio',--}}
{{--                        'accept' => "audio/mpeg3"--}}
{{--                        ]) !!}--}}
{{--                    <div class="photo-block mt-3">--}}
{{--                        <div class="files-list">--}}
{{--                            @if($assignment->mediaAudio)--}}
{{--                                <p class="form-group">--}}
{{--                                    <a href="{{ asset('storage/uploads/'.$assignment->mediaAudio->name) }}"--}}
{{--                                       target="_blank">{{ $assignment->mediaAudio->name }}--}}
{{--                                        ({{ $assignment->mediaAudio->size }} KB)</a>--}}
{{--                                    <a href="#" data-media-id="{{$assignment->mediaAudio->id}}"--}}
{{--                                       class="btn btn-xs btn-danger delete remove-file">@lang('labels.backend.assignments.remove')</a>--}}
{{--                                    <audio id="player" controls>--}}
{{--                                        <source src="{{ $assignment->mediaAudio->url }}" type="audio/mp3" />--}}
{{--                                    </audio>--}}
{{--                                </p>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="row">--}}
{{--                <div class="col-md-12 form-group">--}}
{{--                    {!! Form::label('add_video', trans('labels.backend.assignments.fields.add_video'), ['class' => 'control-label']) !!}--}}
{{--                    {!! Form::select('media_type', ['youtube' => 'Youtube','vimeo' => 'Vimeo','upload' => 'Upload','embed' => 'Embed'],($assignment->mediavideo) ? $assignment->mediavideo->type : null,['class' => 'form-control', 'placeholder' => 'Select One','id'=>'media_type' ]) !!}--}}


{{--                    {!! Form::text('video', ($assignment->mediavideo) ? $assignment->mediavideo->url : null, ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.assignments.enter_video_url'),'id'=>'video'  ]) !!}--}}

{{--                    {!! Form::file('video_file', ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.assignments.enter_video_url'),'id'=>'video_file','accept' =>'video/mp4'  ]) !!}--}}
{{--                    <input type="hidden" name="old_video_file"--}}
{{--                           value="{{($assignment->mediavideo && $assignment->mediavideo->type == 'upload') ? $assignment->mediavideo->url  : ""}}">--}}


{{--                    @if($assignment->mediavideo && ($assignment->mediavideo->type == 'upload'))--}}
{{--                        <video width="300" class="mt-2 d-none video-player" controls>--}}
{{--                            <source src="{{($assignment->mediavideo && $assignment->mediavideo->type == 'upload') ? $assignment->mediavideo->url  : ""}}"--}}
{{--                                    type="video/mp4">--}}
{{--                            Your browser does not support HTML5 video.--}}
{{--                        </video>--}}
{{--                    @endif--}}

{{--                    @lang('labels.backend.assignments.video_guide')--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="row">
                <div class="col-12 col-lg-3  form-group">
                    {!! Form::hidden('rearrangement', 0) !!}
                    {!! Form::checkbox('rearrangement', 1, old('rearrangement'), ['onchange' => 'rearrangementCheck()']) !!}
                    {!! Form::label('rearrangement', trans('labels.backend.assignments.fields.rearrangement'), ['class' => 'control-label control-label font-weight-bold']) !!}
                </div>
            </div>
            <div class="row">

                <div class="col-12 col-lg-3 form-group" name="optionsRe" style="display: none;">
                    <div class="radio">
                        {!! Form::radio('rearrangement_type', 'admin', old('rearrangement_type') == 'admin', []) !!}
                        {!! Form::label('rearrangement_type', 'Admin Upload', ['class' => 'checkbox control-label font-weight-bold']) !!}
                    </div>
                </div>
                <div class="col-12 col-lg-3 form-group" name="optionsRe" style="display: none;">
                    <div class="radio">
                        {!! Form::radio('rearrangement_type', 'student', old('rearrangement_type') == 'student', []) !!}
                        {!! Form::label('rearrangement_type', 'Student Upload', ['class' => 'checkbox control-label font-weight-bold']) !!}
                    </div>
                </div>
                <div class="col-12  text-left form-group">
                    {!! Form::submit(trans('strings.backend.general.app_update'), ['class' => 'btn  btn-primary']) !!}
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="lesson_id" value="{{request('lesson_id')}}"/>
    {!! Form::close() !!}
@stop

@push('after-scripts')
    <script src="{{asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.js')}}"></script>

    <script type="text/javascript" src="{{asset('/vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{asset('/vendor/unisharp/laravel-ckeditor/adapters/jquery.js')}}"></script>
    <script src="{{asset('/vendor/laravel-filemanager/js/lfm.js')}}"></script>
    <script>

        function rearrangementCheck(){
            var chk = document.getElementsByName('rearrangement')[1];
            var opts = document.getElementsByName('optionsRe');

            for (var i=0; i<opts.length; i++) {
                if(chk.checked){
                    opts[i].style.display = 'block';
                }else{
                    opts[i].style.display = 'none';
                }
            }

        }

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

        $(document).ready(function () {
            rearrangementCheck();

            $(document).on('click', '.delete', function (e) {
                e.preventDefault();
                var parent = $(this).parent('.form-group');
                var confirmation = confirm('{{trans('strings.backend.general.are_you_sure')}}')
                if (confirmation) {
                    var media_id = $(this).data('media-id');
                    $.post('{{route('admin.media.destroy')}}', {media_id: media_id, _token: '{{csrf_token()}}'},
                        function (data, status) {
                            if (data.success) {
                                parent.remove();
                            } else {
                                alert('Something Went Wrong')
                            }
                        });
                }
            })
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

        @if($assignment->mediavideo)
        @if($assignment->mediavideo->type !=  'upload')
        $('#video').removeClass('d-none').attr('required', true);
        $('#video_file').addClass('d-none').attr('required', false);
        $('.video-player').addClass('d-none');
        @elseif($assignment->mediavideo->type == 'upload')
        $('#video').addClass('d-none').attr('required', false);
        $('#video_file').removeClass('d-none').attr('required', false);
        $('.video-player').removeClass('d-none');
        @else
        $('.video-player').addClass('d-none');
        $('#video_file').addClass('d-none').attr('required', false);
        $('#video').addClass('d-none').attr('required', false);
        @endif
        @endif

        $(document).on('change', '#media_type', function () {
            if ($(this).val()) {
                if ($(this).val() != 'upload') {
                    $('#video').removeClass('d-none').attr('required', true);
                    $('#video_file').addClass('d-none').attr('required', false);
                    $('.video-player').addClass('d-none')
                } else if ($(this).val() == 'upload') {
                    $('#video').addClass('d-none').attr('required', false);
                    $('#video_file').removeClass('d-none').attr('required', true);
                    $('.video-player').removeClass('d-none')
                }
            } else {
                $('#video_file').addClass('d-none').attr('required', false);
                $('#video').addClass('d-none').attr('required', false)
            }
        })

    </script>
@endpush