@extends('backend.layouts.app')
@section('title', 'Gift | '.app_name())

@section('content')

    {!! Form::open(['method' => 'POST', 'route' => ['admin.gifts.store'], 'files' => true]) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left">Gift Create</h3>
            <div class="float-right">
                <a href="{{ route('admin.gifts.index') }}"
                   class="btn btn-success">View Gift</a>
            </div>
        </div>

        <div class="card-body">

            <div class="row">
                <div class="col-10 form-group">
                    {!! Form::label('category_id',trans('labels.backend.courses.fields.category'), ['class' => 'control-label']) !!}
                    {!! Form::select('category_id', $categories, old('category_id'), ['class' => 'form-control select2 js-example-placeholder-single', 'multiple' => false,'onChange' => 'onCategoryChange()']) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-10 form-group">
                    {!! Form::label('course_id','Course', ['class' => 'control-label']) !!}
                    {!! Form::select('course_id', $courses, old('course_id'), ['class' => 'form-control select2 js-example-placeholder-single', 'multiple' => false, 'disabled']) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('title', trans('labels.backend.courses.fields.title').' *', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.title'), 'required' => false]) !!}
                </div>
            </div>
            <div class="row">

                <div class="col-12 form-group">
                    {!! Form::label('description',  trans('labels.backend.courses.fields.description'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('description', old('description'), ['class' => 'form-control editor', 'placeholder' => trans('labels.backend.courses.fields.description'), 'id' => 'editor']) !!}

                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('lesson_amount',  'Amount of Lessons', ['class' => 'control-label']) !!}
                    {!! Form::number('lesson_amount', old('lesson_amount'), ['class' => 'form-control']) !!}
                </div>
                <div class="col-12 col-lg-4 form-group">
                    {!! Form::label('price',  trans('labels.backend.courses.fields.price').' (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                    {!! Form::number('price', old('price'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.price'),'step' => 'any', 'pattern' => "[0-9]"]) !!}
                </div>
            </div>

                <div class="row">
                    <div class="col-12 form-group">
                        <div class="checkbox d-inline mr-3">
                            {!! Form::hidden('is_skype', 0) !!}
                            {!! Form::checkbox('is_skype', 1, false) !!}
                            {!! Form::label('is_skype',  'With Skype', ['class' => 'checkbox control-label font-weight-bold']) !!}
                        </div>

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


    </script>
    <script>

        function onCategoryChange(){
            var chk = document.getElementsByName('category_id')[0];
            var course = document.getElementsByName('course_id')[0];
            if(chk.value == '1'){
                course.disabled = false;
            }else{
                course.selectedIndex = 0;
                course.value = course.options[0].value;
                course.disabled = true;
            }
        }


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