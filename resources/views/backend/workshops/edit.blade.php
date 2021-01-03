@extends('backend.layouts.app')
@section('title', __('labels.backend.workshops.title').' | '.app_name())

@section('content')

    {!! Form::model($workshop, ['method' => 'PUT', 'route' => ['admin.workshops.update', $workshop->id], 'files' => true,]) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">@lang('labels.backend.workshops.edit')</h3>
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
                        {!! Form::select('teachers[]', $teachers, old('teachers') ? old('teachers') : $workshop->teachers->pluck('id')->toArray(), ['class' => 'form-control select2', 'multiple' => 'multiple','required' => true]) !!}
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
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                </div>
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('slug', trans('labels.backend.workshops.fields.slug'), ['class' => 'control-label']) !!}
                    {!! Form::text('slug', old('slug'), ['class' => 'form-control', 'placeholder' =>  trans('labels.backend.workshops.slug_placeholder')]) !!}
                </div>

            </div>

            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('description',trans('labels.backend.workshops.fields.description'), ['class' => 'control-label']) !!}
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

{{--                <div class="col-12 col-lg-4 form-group">--}}
{{--                    {!! Form::label('start_date', trans('labels.backend.workshops.fields.start_date').' (yyyy-mm-dd)', ['class' => 'control-label']) !!}--}}
{{--                    {!! Form::text('start_date', old('start_date'), ['class' => 'form-control date', 'required' => true, 'pattern' => '(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))', 'placeholder' => trans('labels.backend.workshops.fields.start_date').' (Ex . 2019-01-01)']) !!}--}}
{{--                    <p class="help-block"></p>--}}
{{--                    @if($errors->has('start_date'))--}}
{{--                        <p class="help-block">--}}
{{--                            {{ $errors->first('start_date') }}--}}
{{--                        </p>--}}
{{--                    @endif--}}
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

{{--                        {!! Form::label('workshop_image', trans('labels.backend.workshops.fields.workshop_image'), ['class' => 'control-label','accept' => 'image/jpeg,image/gif,image/png']) !!}--}}
{{--                        {!! Form::file('workshop_image', ['class' => 'form-control']) !!}--}}
{{--                        {!! Form::hidden('workshop_image_max_size', 8) !!}--}}
{{--                        {!! Form::hidden('workshop_image_max_width', 4000) !!}--}}
{{--                        {!! Form::hidden('workshop_image_max_height', 4000) !!}--}}

{{--                    </div>--}}
                </div>
                <div class="row">
                    @if (count($workshop->image)>0)
                        @foreach($workshop->image as $image)
                            <div class="col-12 col-lg-3 form-group">
                                <div class="checkbox d-inline mr-4">
                                    <a href="{{ asset('storage/uploads/'.$image) }}" target="_blank"><img
                                                height="50px" src="{{ asset('storage/uploads/'.$image) }}"
                                                class="mt-1"></a>
                                    {!! Form::hidden('published', 0) !!}
                                    {!! Form::checkbox('delete[]', $image, (old('delete') && in_array($image, old('delete'))), []) !!}
                                    {!! Form::label('delete', trans('labels.general.delete'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            <div class="row">
                <div class="col-md-12 form-group">
                    {!! Form::label('add_video', trans('labels.backend.lessons.fields.add_video'), ['class' => 'control-label']) !!}
                    {!! Form::select('media_type', ['youtube' => 'Youtube','vimeo' => 'Vimeo','upload' => 'Upload','embed' => 'Embed'],($workshop->mediavideo) ? $workshop->mediavideo->type : null,['class' => 'form-control', 'placeholder' => 'Select One','id'=>'media_type' ]) !!}


                    {!! Form::text('video', ($workshop->mediavideo) ? $workshop->mediavideo->url : null, ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>'video'  ]) !!}

                    {!! Form::file('video_file', ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>'video_file','accept' =>'video/mp4'  ]) !!}
                    <input type="hidden" name="old_video_file"
                           value="{{($workshop->mediavideo && $workshop->mediavideo->type == 'upload') ? $workshop->mediavideo->url  : ""}}">
                    @if($workshop->mediavideo != null)
                        <div class="form-group">
                            <a href="#" data-media-id="{{$workshop->mediaVideo->id}}"
                               class="btn btn-xs btn-danger my-3 delete remove-file">@lang('labels.backend.lessons.remove')</a>
                        </div>
                    @endif



                    @if($workshop->mediavideo && ($workshop->mediavideo->type == 'upload'))
                        <video width="300" class="mt-2 d-none video-player" controls>
                            <source src="{{($workshop->mediavideo && $workshop->mediavideo->type == 'upload') ? $workshop->mediavideo->url  : ""}}"
                                    type="video/mp4">
                            Your browser does not support HTML5 video.
                        </video>

                    @endif

                    @lang('labels.backend.lessons.video_guide')
                </div>
            </div>

            <div class="row">
                <div class="col-12 form-group">
                    <div class="checkbox d-inline mr-4">
                        {!! Form::hidden('published', 0) !!}
                        {!! Form::checkbox('published', 1, old('published'), []) !!}
                        {!! Form::label('published', trans('labels.backend.workshops.fields.published'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                    </div>

{{--                    <div class="checkbox d-inline mr-4">--}}
{{--                        {!! Form::hidden('free', 0) !!}--}}
{{--                        {!! Form::checkbox('free', 1, old('free'), []) !!}--}}
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
                    {!! Form::submit(trans('strings.backend.general.app_update'), ['class' => 'btn btn-danger']) !!}
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
        $(document).on('change', 'input[type="file"]', function () {
            var $this = $(this);
            $(this.files).each(function (key, value) {
                if (value.size > 50000000) {
                    alert('"' + value.name + '"' + 'exceeds limit of maximum file upload size')
                    $this.val("");
                }
            })
        });

        $(document).ready(function () {
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
                                $('#video').val('').addClass('d-none').attr('required', false);
                                $('#video_file').attr('required', false);
                                $('#media_type').val('');
                                @if($workshop->mediavideo && $workshop->mediavideo->type ==  'upload')
                                $('.video-player').addClass('d-none');
                                $('.video-player').empty();
                                @endif


                            } else {
                                alert('Something Went Wrong')
                            }
                        });
                }
            })
        });


        @if($workshop->mediavideo)
        @if($workshop->mediavideo->type !=  'upload')
        $('#video').removeClass('d-none').attr('required', true);
        $('#video_file').addClass('d-none').attr('required', false);
        $('.video-player').addClass('d-none');
        @elseif($workshop->mediavideo->type == 'upload')
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