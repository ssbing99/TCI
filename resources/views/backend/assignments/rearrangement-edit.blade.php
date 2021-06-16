@extends('backend.layouts.app')
@section('title', 'Attachment | '.app_name())

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

    {!! Form::model($attachment, ['method' => 'POST', 'route' => ['admin.assignments.rearrangement.update', [$assignment->id, $attachment->id]], 'files' => true,]) !!}
    {!! Form::hidden('model_id',0,['id'=>'assignment_id']) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">Edit Rearrangement</h3>
            <div class="float-right">
                <a href="{{ route('admin.assignments.rearrangement.list', ['assignment_id'=>$assignment->id]) }}"
                   class="btn btn-success">View Rearrangement</a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('title', trans('labels.backend.lessons.fields.title'), ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.lessons.fields.title'), 'required' => true]) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('full_text', 'Description', ['class' => 'control-label']) !!}
                    {!! Form::textarea('full_text', old('full_text'), ['class' => 'form-control editor', 'placeholder' => '','id' => 'editor', 'required' => true]) !!}

                </div>
            </div>


            <div class="row">

                <div class="col-12  text-left form-group">
                    {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn  btn-danger']) !!}
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

        // var uploadField = $('input[type="file"]');

        // $(document).on('change', 'input[name="attachment_file"]', function () {
        //     var $this = $(this);
        //     $(this.files).each(function (key, value) {
        //         if (value.size > 5000000) {
        //             alert('"' + value.name + '"' + 'exceeds limit of maximum file upload size')
        //             $this.val("");
        //         }
        //     })
        // });


    </script>

@endpush