@extends('backend.layouts.app')
@section('title', __('labels.backend.workshops.title').' | '.app_name())

@section('content')

    {!! Form::open(['method' => 'POST', 'route' => ['admin.workshops.store'], 'files' => true]) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left">@lang('labels.backend.workshops.create')</h3>
            <div class="float-right">
                <a href="{{ route('admin.workshops.index') }}"
                   class="btn btn-success">@lang('labels.backend.workshops.view')</a>
            </div>
        </div>

        <div class="card-body">
            @if (Auth::user()->isAdmin())
                <div class="row">
                    <div class="col-10 form-group">
                        {!! Form::label('teachers',trans('labels.backend.workshops.fields.teachers'), ['class' => 'control-label']) !!}
                        {!! Form::select('teachers[]', $teachers, old('teachers'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple', 'required' => true]) !!}
                    </div>
                    <div class="col-2 d-flex form-group flex-column">
                        OR <a target="_blank" class="btn btn-primary mt-auto"
                              href="{{route('admin.teachers.create')}}">{{trans('labels.backend.workshops.add_teachers')}}</a>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('title', trans('labels.backend.workshops.fields.title').' *', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.workshops.fields.title'), 'required' => false]) !!}
                </div>
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('slug',  trans('labels.backend.workshops.fields.slug'), ['class' => 'control-label']) !!}
                    {!! Form::text('slug', old('slug'), ['class' => 'form-control', 'placeholder' =>  trans('labels.backend.workshops.slug_placeholder')]) !!}

                </div>
            </div>
            <div class="row">

                <div class="col-12 form-group">
                    {!! Form::label('description',  trans('labels.backend.workshops.fields.description'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('description', old('description'), ['class' => 'form-control editor', 'placeholder' => trans('labels.backend.workshops.fields.description'), 'id' => 'editor']) !!}

                </div>
            </div>
                <div class="row">

                    <div class="col-12 form-group">
                        {!! Form::label('enrolment_details',  'Enrolment Details', ['class' => 'control-label']) !!}
                        {!! Form::textarea('enrolment_details', old('enrolment_details'), ['class' => 'form-control editor', 'placeholder' => trans('labels.backend.workshops.fields.description'), 'id' => 'editorE']) !!}

                    </div>
                </div>
                <div class="row">

                    <div class="col-12 form-group">
                        {!! Form::label('upcoming_workshop',  'Upcoming Workshop', ['class' => 'control-label']) !!}
                        {!! Form::textarea('upcoming_workshop', old('upcoming_workshop'), ['class' => 'form-control editor', 'placeholder' => trans('labels.backend.workshops.fields.description'), 'id' => 'editorU']) !!}

                    </div>
                </div>
            <div class="row">
                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('price',  trans('labels.backend.workshops.fields.price').' (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                    {!! Form::number('price', old('price'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.workshops.fields.price'),'step' => 'any', 'pattern' => "[0-9]"]) !!}
                </div>
                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('deposit',  'Deposit'.' (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                    {!! Form::number('deposit', old('deposit'), ['class' => 'form-control', 'placeholder' => 'Deposit','step' => 'any', 'pattern' => "[0-9]"]) !!}
                </div>
                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('balance',  'Balance'.' (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                    {!! Form::number('balance', old('balance'), ['class' => 'form-control', 'placeholder' => 'Balance','step' => 'any', 'pattern' => "[0-9]"]) !!}
                </div>
                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('single_supplement',  'Single Supplement'.' (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                    {!! Form::number('single_supplement', old('single_supplement'), ['class' => 'form-control', 'placeholder' => 'Single Supplement','step' => 'any', 'pattern' => "[0-9]"]) !!}
                </div>
                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('workshop_date',  'Workshop Dates (Free text)', ['class' => 'control-label']) !!}
                    {!! Form::text('workshop_date', old('workshop_date'), ['class' => 'form-control', 'placeholder' => '']) !!}
                </div>

{{--                <div class="col-12 col-lg-4  form-group">--}}
{{--                    {!! Form::label('start_date', trans('labels.backend.workshops.fields.start_date').' (yyyy-mm-dd)', ['class' => 'control-label']) !!}--}}
{{--                    {!! Form::text('start_date', old('start_date'), ['class' => 'form-control date', 'required' => true, 'pattern' => '(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))', 'placeholder' => trans('labels.backend.workshops.fields.start_date').' (Ex . 2019-01-01)', 'autocomplete' => 'off']) !!}--}}

{{--                </div>--}}
            </div>
                <?php $imgCnt = 1; ?>
                <div class="row">
                    @for($i=1; $i<=6; $i++)
                    <div class="col-12 col-lg-4 form-group">
                        {!! Form::label('workshop_image',  'Photo '.($i), ['class' => 'control-label']) !!}
                        {!! Form::file('workshop_image[]',  ['class' => 'form-control', 'required' => false, 'accept' => 'image/jpeg,image/gif,image/png']) !!}
                        {!! Form::hidden('workshop_image_max_size', 8) !!}
                        {!! Form::hidden('workshop_image_max_width', 4000) !!}
                        {!! Form::hidden('workshop_image_max_height', 4000) !!}

                    </div>
                    @endfor
{{--                    <div class="col-12 col-lg-4 form-group">--}}
{{--                        {!! Form::label('workshop_image',  'Photo '.($imgCnt++), ['class' => 'control-label']) !!}--}}
{{--                        {!! Form::file('workshop_image[]',  ['class' => 'form-control', 'required' => false, 'accept' => 'image/jpeg,image/gif,image/png']) !!}--}}
{{--                        {!! Form::hidden('workshop_image_max_size', 8) !!}--}}
{{--                        {!! Form::hidden('workshop_image_max_width', 4000) !!}--}}
{{--                        {!! Form::hidden('workshop_image_max_height', 4000) !!}--}}

{{--                    </div>--}}


                </div>
                <div class="row">
                    <div class="col-md-12 form-group">
                        {!! Form::label('add_video', trans('labels.backend.lessons.fields.add_video'), ['class' => 'control-label']) !!}

                        {!! Form::select('media_type', ['youtube' => 'Youtube','vimeo' => 'Vimeo','upload' => 'Upload','embed' => 'Embed'],null,['class' => 'form-control', 'placeholder' => 'Select One','id'=>'media_type' ]) !!}

                        {!! Form::text('video', old('video'), ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>'video'  ]) !!}


                        {!! Form::file('video_file', ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>'video_file'  ]) !!}

                    </div>
                    {{--<div class="col-md-12 form-group d-none" id="video_subtitle_box">--}}

                        {{--{!! Form::label('add_subtitle', trans('labels.backend.lessons.fields.add_subtitle'), ['class' => 'control-label']) !!}--}}

                        {{--{!! Form::file('video_subtitle', ['class' => 'form-control', 'placeholder' => trans('labels.backend.lessons.video_subtitle'),'id'=>'video_subtitle'  ]) !!}--}}

                    {{--</div>--}}
                    <div class="col-md-12 form-group">

                    @lang('labels.backend.lessons.video_guide')
                    </div>

                </div>

                <div class="row">
                <div class="col-12 form-group">
                    <div class="checkbox d-inline mr-3">
                        {!! Form::hidden('published', 0) !!}
                        {!! Form::checkbox('published', 1, false, []) !!}
                        {!! Form::label('published',  trans('labels.backend.workshops.fields.published'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                    </div>

{{--                    <div class="checkbox d-inline mr-3">--}}
{{--                        {!! Form::hidden('free', 0) !!}--}}
{{--                        {!! Form::checkbox('free', 1, false, []) !!}--}}
{{--                        {!! Form::label('free',  trans('labels.backend.workshops.fields.free'), ['class' => 'checkbox control-label font-weight-bold']) !!}--}}
{{--                    </div>--}}


                </div>

            </div>

            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('meta_title',trans('labels.backend.workshops.fields.meta_title'), ['class' => 'control-label']) !!}
                    {!! Form::text('meta_title', old('meta_title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.workshops.fields.meta_title')]) !!}

                </div>
                <div class="col-12 form-group">
                    {!! Form::label('meta_description',trans('labels.backend.workshops.fields.meta_description'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('meta_description', old('meta_description'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.workshops.fields.meta_description')]) !!}
                </div>
                <div class="col-12 form-group">
                    {!! Form::label('meta_keywords',trans('labels.backend.workshops.fields.meta_keywords'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('meta_keywords', old('meta_keywords'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.workshops.fields.meta_keywords')]) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-12  text-center form-group">

                    {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn btn-lg btn-danger']) !!}
                </div>
            </div>
        </div>
    </div>
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

        $('.editorE').each(function () {

            CKEDITOR.replace($(this).attr('id'), {
                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()}}',

                extraPlugins: 'smiley,lineutils,widget,codesnippet,prism,flash',
            });

        });

        $('.editorU').each(function () {

            CKEDITOR.replace($(this).attr('id'), {
                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()}}',

                extraPlugins: 'smiley,lineutils,widget,codesnippet,prism,flash',
            });

        });
        var uploadField = $('input[type="file"]');

        $(document).on('change','input[type="file"]',function () {
            var $this = $(this);
            $(this.files).each(function (key,value) {
                if((value.size/1024) > 10240){
                    alert('"'+value.name+'"'+'exceeds limit of maximum file upload size' )
                    $this.val("");
                }
            })
        })

    </script>
    <script>

        $(document).ready(function () {
            $('#start_date').datepicker({
                autoclose: true,
                dateFormat: "{{ config('app.date_format_js') }}"
            });

            $(".js-example-placeholder-single").select2({
                placeholder: "{{trans('labels.backend.workshops.select_category')}}",
            });

            $(".js-example-placeholder-multiple").select2({
                placeholder: "{{trans('labels.backend.workshops.select_teachers')}}",
            });
        });

        var uploadField = $('input[type="file"]');

        $(document).on('change', 'input[type="file"]', function () {
            var $this = $(this);
            $(this.files).each(function (key, value) {
                if (value.size > 5000000) {
                    alert('"' + value.name + '"' + 'exceeds limit of maximum file upload size')
                    $this.val("");
                }
            })
        })


        $(document).on('change', '#media_type', function () {
            if ($(this).val()) {
                if ($(this).val() != 'upload') {
                    $('#video').removeClass('d-none').attr('required', true)
                    $('#video_file').addClass('d-none').attr('required', false)
//                    $('#video_subtitle_box').addClass('d-none').attr('required', false)

                } else if ($(this).val() == 'upload') {
                    $('#video').addClass('d-none').attr('required', false)
                    $('#video_file').removeClass('d-none').attr('required', true)
//                    $('#video_subtitle_box').removeClass('d-none').attr('required', true)
                }
            } else {
                $('#video_file').addClass('d-none').attr('required', false)
//                $('#video_subtitle_box').addClass('d-none').attr('required', false)
                $('#video').addClass('d-none').attr('required', false)
            }
        })


    </script>

@endpush