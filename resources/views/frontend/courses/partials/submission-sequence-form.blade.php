
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

    <!-- Start of breadcrumb section
            ============================================= -->
<div class="row clearfix">
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
        <h3>Assignment Submission</h3>
    </div>
</div>

<div class="row clearfix">
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
{{--                            <button type="button" class="btn btn-danger" onclick="winGo('{{route('admin.assignments.attachment.delete',['assignment_id'=> $assignment->id, 'id'=> $item->id, 'group_id' => $attachment->id])}}')"><i class="fa fa-trash"></i></button>--}}
                            <button type="button" class="btn btn-danger" onclick="deleteItem('{{$item->id}}')"><i class="fa fa-trash"></i></button>

                        </div>
                    </div>

                @endif

            @endforeach
        </div>

        <input type="hidden" name="changeSeq" id="changeSeq" value="">
        <br />
{{--        <h4><a href="{{route('submission.show', $assignment->id)}}" class="btn btn-primary btn-padding br-24">Back</a></h4>--}}
    </div>
</div>


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

        function updateChangeSeq(itemId) {
            var productOrder = $('#imageListId').sortable("toArray").toString();
            console.log(productOrder);
            var ii = 1;

            //Remove
            if(itemId != '' && attach_map.has(itemId)) {
                attach_map.set(itemId, 0);
            }

            for(var ord of productOrder.split(',')){
                attach_map.set(ord, ii++);
            }

            attach_map.forEach((value, key) => {
                var keys = key.split('.'),
                    last = keys.pop();
                keys.reduce((r, a) => r[a] = r[a] || {}, object)[last] = value;
            });
            console.log(object);
            document.getElementById('changeSeq').value = JSON.stringify(object);
        }

        function seqFormSubmit(thisform) {
            updateChangeSeq('');
            thisform.submit();
        }

        function deleteItem(itemId){
            console.log($('#imageListId').sortable("toArray"));
            // $('#'+itemId).hide('1000');
            $('#'+itemId).remove();

            updateChangeSeq(itemId);
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
                    console.log(object);
                    document.getElementById('changeSeq').value = JSON.stringify(object);
                },
            });
        });

        $(document).ready(function () {

        });


    </script>

@endpush