@extends('backend.layouts.app')
@section('title', 'Rearrangement | '.app_name())

@push('after-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/amigo-sorter/css/theme-default.css')}}">
    <style>
        ul.sorter > span {
            display: inline-block;
            width: 100%;
            height: 100%;
            background: #f5f5f5;
            color: #333333;
            border: 1px solid #cccccc;
            border-radius: 6px;
            padding: 0px;
        }

        ul.sorter li > span .title {
            padding-left: 15px;
            width: 70%;
        }

        ul.sorter li > span .btn {
            width: 20%;
        }

        @media screen and (max-width: 768px) {

            ul.sorter li > span .btn {
                width: 30%;
            }

            ul.sorter li > span .title {
                padding-left: 15px;
                width: 70%;
                float: left;
                margin: 0 !important;
            }

        }

        .flex {
            -webkit-box-flex: 1;
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
        }

        .list-item {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word
        }

        .list-item.block .media {
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0
        }

        .list-item.block .list-content {
            padding: 1rem
        }

        .list-item img {
            width: 60px;
            height: 60px;
            display: block;
            border-radius: 0;
            border: none;
        }

        .list-row .list-item {
            -ms-flex-direction: row;
            flex-direction: row;
            -ms-flex-align: center;
            align-items: center;
            padding: .75rem .625rem
        }
        .list-item {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word
        }
        .list-row .list-item>* {
            padding-left: .625rem;
            padding-right: .625rem
        }
        list-item {
            background: white
        }


    </style>
@endpush

@section('content')

    {!! Form::open(['method' => 'POST', 'route' => ['admin.gallery.store'], 'files' => true]) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left">Gallery</h3>
        </div>

        <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-6 form-group">
                        {!! Form::label('student_id', 'Student', ['class' => 'control-label']) !!}
                        {!! Form::select('student_id', $students, old('student_id'), ['class' => 'form-control select2']) !!}
                    </div>
                    <div class="col-12 col-lg-6 form-group">
                        {!! Form::label('course_name', 'Course Name', ['class' => 'control-label']) !!}
                        {!! Form::text('course_name', old('course_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-6 form-group">
                        {!! Form::label('gallery_file', 'Upload a File', ['class' => 'control-label']) !!}
                        {!! Form::file('gallery_file', [
                            'class' => 'form-control file-upload',
                             'id' => 'gallery_file',
                            'accept' => "image/*",
                            'required' => true
                            ]) !!}
                    </div>
                </div>

            <div class="row">
                <div class="col-12  text-center form-group">

                    {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn btn-lg btn-danger']) !!}
                </div>
            </div>


            @if(count($galleries) > 0)
                <div class="list list-row card" data-sortable-id="0">

                    @foreach($galleries as $item)
                        @if(isset($item->media) && !$item->media->isEmpty())
                            <div class="list-item" data-id="{{$item->position}}" data-attach="{{$item->id}}" id="{{$item->id}}" data-item-sortable-id="0" role="option" aria-grabbed="false" style="">
                                @php $_media = $item->media->first(); @endphp
                                {{--                                @foreach($item->media as $_media)--}}
                                @if(str_contains($_media->type,'image'))
                                    <div><a href="{{ asset('storage/uploads/'.$_media->name) }}" target="_blank"><img src="{{ asset('storage/uploads/'.$_media->name) }}" alt="" /></a></div>
                                @endif
                                {{--                                @endforeach--}}
                                <div class="flex">
                                    <a href="#" class="item-author text-color" data-abc="true">{{ $item->title }}</a>
                                    <div class="item-except text-muted text-sm h-1x">{{substr(strip_tags($item->description),0, 100).(empty($item->description)?'':'...')}}</div>
                                </div>
                                <div class="no-wrap">
                                    <div class="item-date text-muted text-sm d-none d-md-block">{{$item->created_at->diffforhumans()}}</div>
                                </div>
                                <div>
                                    <div class="btn-group btn-group-sm" role="group" aria-label="First group">

                                        @php $_media = $item->media->first(); @endphp
                                        {{--                                        @foreach($item->media as $_media)--}}
                                        @if(str_contains($_media->type,'image'))
                                            <button type="button" class="btn btn-outline-secondary" onclick="winOpen('{{ asset('storage/uploads/'.$_media->name) }}')"><i class="fa fa-search"></i></button>
                                        @endif
                                        {{--                                        @endforeach--}}

                                        <button type="button" class="btn btn-outline-secondary" onclick="winGo('{{route('admin.gallery.delete',['id'=> $item->id, 'id'=> $item->id])}}')"><i class="fa fa-trash"></i></button>
                                    </div>
                                </div>
                            </div>

                        @endif

                    @endforeach

                </div>
            @endif

        </div>


    </div>
    {!! Form::close() !!}

@stop

@push('after-scripts')
    <script src="{{asset('assets_new/js/jquery-ui.js')}}"></script>
{{--    <script src="{{asset('plugins/amigo-sorter/js/amigo-sorter.min.js')}}"></script>--}}
    <script>

        var attach_map = new Map();
        var object = {};

        $(document).on('change', 'input[name="gallery_file"]', function () {
            var $this = $(this);
            $(this.files).each(function (key, value) {
                if (value.size > 5000000) {
                    alert('"' + value.name + '"' + 'exceeds limit of maximum file upload size')
                    $this.val("");
                }
            })
        });

        function winOpen(url){
            window.open(url, "_blank");
        }
        function winGo(url){
            location.href = url;
        }

        $(function() {
            // $("#sortable").sortable({
            //     start: function(e, ui) {
            //         // creates a temporary attribute on the element with the old index
            //         $(this).attr('data-previndex', ui.item.index()+1);
            //     },
            //     update: function(event, ui) {
            //         var newIndex = ui.item.index() + 1;
            //         var oldIndex = $(this).attr('data-previndex');
            //         var element_id = ui.item.attr('data-id');
            //         var attach_id = ui.item.attr('data-attach');
            //         // console.log('id of Item'+attach_id+' moved = '+element_id+' old position = '+oldIndex+' new position = '+newIndex);
            //         $(this).removeAttr('data-previndex');
            //
            //         var productOrder = $(this).sortable('toArray').toString();
            //
            //
            //         var ii = 1;
            //         for(var ord of productOrder.split(',')){
            //             attach_map.set(ord, ii++);
            //         }
            //         attach_map.forEach((value, key) => {
            //             var keys = key.split('.'),
            //                 last = keys.pop();
            //             keys.reduce((r, a) => r[a] = r[a] || {}, object)[last] = value;
            //         });
            //
            //         document.getElementById('changeSeq').value = JSON.stringify(object);
            //     },
            // });
            // $("#sortable").disableSelection();
        });

        {{--$(function () {--}}
        {{--    $('ul.sorter').amigoSorter({--}}
        {{--        li_helper: "li_helper",--}}
        {{--        li_empty: "empty",--}}
        {{--    });--}}
        {{--    $(document).on('click', '#save_timeline', function (e) {--}}
        {{--        e.preventDefault();--}}
        {{--        var list = [];--}}
        {{--        $('ul.sorter li').each(function (key, value) {--}}
        {{--            key++;--}}
        {{--            var val = $(value).find('span').data('id');--}}
        {{--            list.push({id: val, sequence: key});--}}
        {{--        });--}}

        {{--        $.ajax({--}}
        {{--            method: 'POST',--}}
        {{--            url: "{{route('admin.courses.saveSequence')}}",--}}
        {{--            data: {--}}
        {{--                _token: '{{csrf_token()}}',--}}
        {{--                list: list--}}
        {{--            }--}}
        {{--        }).done(function () {--}}
        {{--            location.reload();--}}
        {{--        });--}}
        {{--    })--}}
        {{--});--}}

    </script>
@endpush