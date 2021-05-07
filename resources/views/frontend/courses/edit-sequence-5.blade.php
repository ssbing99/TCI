@extends('frontend.layouts.app'.config('theme_layout'))

@push('after-styles')
    <style>
        .list-item img {
            width: 60px;
            height: 60px;
            display: block;
            border-radius: 0;
            border: none;
        }
    </style>
@endpush

@section('content')
    <!-- Start of breadcrumb section
            ============================================= -->
    <header>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <h1>Edit Assignment Submission</h1>
                </div>
            </div>
        </div>
    </header>
    <!-- End of breadcrumb section
        ============================================= -->

    <!-- Start of course details section
            ============================================= -->
    <section>
        <div class="container">
            <div class="col-12">
                <p class="assign-content clearfix">Drag and drop the images then click on ' Save Position ' button to rearrange the sequence</p>
                <div id="imageListId">
                    @foreach($attachments as $item)
                        @if(isset($item->media) && !$item->media->isEmpty())

                            <div data-id="{{$item->position}}" data-attach="{{$item->id}}" id="{{$item->id}}" class="listitemClass">
                                @php $_media = $item->media->first(); @endphp
                                {{--                                @foreach($item->media as $_media)--}}
                                @if($_media->type == 'upload')
                                    <div><a href="{{$_media->url}}" target="_blank"><img src="{{asset('assets_new/images/play-button.png')}}" alt="" /></a></div>
                                @elseif(str_contains($_media->type,'image'))
                                    <div><a href="{{ asset('storage/uploads/'.$_media->name) }}" target="_blank"><img src="{{ asset('storage/uploads/'.$_media->name) }}" alt="" /></a></div>

                                @elseif(str_contains($_media->type,'youtube'))
                                    <div><a href="https://www.youtube.com/embed/{{$_media->url}}" target="_blank"><img src="https://img.youtube.com/vi/{{$_media->url}}/0.jpg" alt="" /></a></div>
                                @elseif(str_contains($_media->type,'vimeo'))
                                    <div><a href="https://player.vimeo.com/video/{{$_media->url}}" target="_blank"><img src="https://i.vimeocdn.com/video/{{$_media->url}}/0.jpg" alt="" /></a></div>
                                @endif
                                <h6>{{ $item->title }}</h6>
                                <p>{{substr(strip_tags($item->description),0, 100).(empty($item->description)?'':'...')}}</p>
                                <div class="item-date text-muted text-sm d-none d-md-block">{{$item->created_at->diffforhumans()}}</div>
                                <div class="btn-group btn-group-sm" role="group" aria-label="First group">
                                    @php $_media = $item->media->first(); @endphp
                                    {{--                                        @foreach($item->media as $_media)--}}
                                    @if($_media->type == 'upload')
                                        <button type="button" class="btn btn-info" onclick="winOpen('{{$_media->url}}')"><i class="fa fa-search"></i></button>
                                    @elseif(str_contains($_media->type,'image'))
                                        <button type="button" class="btn btn-info" onclick="winOpen('{{ asset('storage/uploads/'.$_media->name) }}')"><i class="fa fa-search"></i></button>
                                    @elseif(str_contains($_media->type,'youtube'))
                                        <button type="button" class="btn btn-info" onclick="winOpen('https://www.youtube.com/embed/{{$_media->url}}')"><i class="fa fa-search"></i></button>
                                    @elseif(str_contains($_media->type,'vimeo'))
                                        <button type="button" class="btn btn-info" onclick="winOpen('https://player.vimeo.com/video/{{$_media->url}}')"><i class="fa fa-search"></i></button>
                                    @endif
                                    <button type="button" class="btn btn-warning" onclick="winGo('{{route('submission.attachment.edit',['assignment_id'=> $assignment->id, 'submission_id'=>$submission->id, 'id'=> $item->id])}}')"><i class="fa fa-pencil"></i></button>
                                    <button type="button" class="btn btn-danger" onclick="winGo('{{route('submission.attachment.delete',['assignment_id'=> $assignment->id, 'submission_id'=>$submission->id, 'id'=> $item->id])}}')"><i class="fa fa-trash"></i></button>

                                </div>
                            </div>

                        @endif

                    @endforeach
                </div>

{{--                <div class="list list-row card" id="sortable" data-sortable-id="0" aria-dropeffect="move">--}}

{{--                    @foreach($attachments as $item)--}}
{{--                        @if(isset($item->media) && !$item->media->isEmpty())--}}
{{--                            <div class="list-item" data-id="{{$item->position}}" data-attach="{{$item->id}}" id="{{$item->id}}" data-item-sortable-id="0" draggable="true" role="option" aria-grabbed="false" style="">--}}
{{--                                @php $_media = $item->media->first(); @endphp--}}
{{--                                @foreach($item->media as $_media)--}}
{{--                                    @if($_media->type == 'upload')--}}
{{--                                        <div><a href="{{$_media->url}}" target="_blank"><img src="{{asset('assets_new/images/play-button.png')}}" alt="" /></a></div>--}}
{{--                                    @elseif(str_contains($_media->type,'image'))--}}
{{--                                        <div><a href="{{ asset('storage/uploads/'.$_media->name) }}" target="_blank"><img src="{{ asset('storage/uploads/'.$_media->name) }}" alt="" /></a></div>--}}

{{--                                    @elseif(str_contains($_media->type,'youtube'))--}}
{{--                                        <div><a href="https://www.youtube.com/embed/{{$_media->url}}" target="_blank"><img src="https://img.youtube.com/vi/{{$_media->url}}/0.jpg" alt="" /></a></div>--}}
{{--                                    @elseif(str_contains($_media->type,'vimeo'))--}}
{{--                                        <div><a href="https://player.vimeo.com/video/{{$_media->url}}" target="_blank"><img src="https://i.vimeocdn.com/video/{{$_media->url}}/0.jpg" alt="" /></a></div>--}}
{{--                                    @endif--}}
{{--                                @endforeach--}}
{{--                                <div class="flex">--}}
{{--                                    <a href="#" class="item-author text-color" data-abc="true">{{ $item->title }}</a>--}}
{{--                                    <div class="item-except text-muted text-sm h-1x">{{substr(strip_tags($item->description),0, 100).(empty($item->description)?'':'...')}}</div>--}}
{{--                                </div>--}}
{{--                                <div class="no-wrap">--}}
{{--                                    <div class="item-date text-muted text-sm d-none d-md-block">{{$item->created_at->diffforhumans()}}</div>--}}
{{--                                </div>--}}
{{--                                <div>--}}
{{--                                    <div class="btn-group btn-group-sm" role="group" aria-label="First group">--}}

{{--                                        @php $_media = $item->media->first(); @endphp--}}
{{--                                        @foreach($item->media as $_media)--}}
{{--                                            @if($_media->type == 'upload')--}}
{{--                                                <button type="button" class="btn btn-outline-secondary" onclick="winOpen('{{$_media->url}}')"><i class="fa fa-search"></i></button>--}}
{{--                                            @elseif(str_contains($_media->type,'image'))--}}
{{--                                                <button type="button" class="btn btn-outline-secondary" onclick="winOpen('{{ asset('storage/uploads/'.$_media->name) }}')"><i class="fa fa-search"></i></button>--}}
{{--                                            @elseif(str_contains($_media->type,'youtube'))--}}
{{--                                                <button type="button" class="btn btn-outline-secondary" onclick="winOpen('https://www.youtube.com/embed/{{$_media->url}}')"><i class="fa fa-search"></i></button>--}}
{{--                                            @elseif(str_contains($_media->type,'vimeo'))--}}
{{--                                                <button type="button" class="btn btn-outline-secondary" onclick="winOpen('https://player.vimeo.com/video/{{$_media->url}}')"><i class="fa fa-search"></i></button>--}}
{{--                                            @endif--}}
{{--                                        @endforeach--}}

{{--                                        <button type="button" class="btn btn-outline-secondary" onclick="winGo('{{route('submission.attachment.edit',['assignment_id'=> $assignment->id, 'submission_id'=>$submission->id, 'id'=> $item->id])}}')"><i class="fa fa-pencil"></i></button>--}}
{{--                                        <button type="button" class="btn btn-outline-secondary" onclick="winGo('{{route('submission.attachment.delete',['assignment_id'=> $assignment->id, 'submission_id'=>$submission->id, 'id'=> $item->id])}}')"><i class="fa fa-trash"></i></button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}



{{--                        @endif--}}

{{--                    @endforeach--}}

{{--                </div>--}}
                <form id="seqForm" name="seqForm" method="post" action="{{route('submission.attachment.sequence.update', ['assignment_id' => $assignment->id, 'submission_id' => $submission->id])}}">
                    @csrf
                    <input type="hidden" name="changeSeq" id="changeSeq" value="">
                    <button type="submit" name="btnSubmit" class="btn btn-primary btn-padding br-24">Save Position</button>
                </form>
            </div>
        </div>
    </section>
    <!-- End of course details section
        ============================================= -->
@endsection

@push('after-scripts')
    <script src="{{asset('assets_new/js/jquery-ui.js')}}"></script>
    <script>

        var attach_map = new Map();
        var object = {};

        function winOpen(url){
            window.open(url, "_blank");
        }
        function winGo(url){
            location.href = url;
        }

        $(function() {
            $("#imageListId").sortable({
                update: function(event, ui) {
                    var newIndex = ui.item.index() + 1;
                    var oldIndex = $(this).attr('data-previndex');
                    var element_id = ui.item.attr('data-id');
                    var attach_id = ui.item.attr('data-attach');
                    // console.log('id of Item'+attach_id+' moved = '+element_id+' old position = '+oldIndex+' new position = '+newIndex);
                    $(this).removeAttr('data-previndex');

                    var productOrder = $(this).sortable('toArray').toString();

                    var ii = 1;
                    for(var ord of productOrder.split(',')){
                        attach_map.set(ord, ii++);
                    }
                    attach_map.forEach((value, key) => {
                        var keys = key.split('.'),
                            last = keys.pop();
                        keys.reduce((r, a) => r[a] = r[a] || {}, object)[last] = value;
                    });

                    document.getElementById('changeSeq').value = JSON.stringify(object);
                },
            });
        });

        // $(function() {
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
        // });

        $(document).ready(function () {

        });


    </script>

@endpush