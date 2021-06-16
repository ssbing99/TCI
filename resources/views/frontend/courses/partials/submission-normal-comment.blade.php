<div class="border-box clearfix">
    <p class="assign-content clearfix">
                                <span>
                                    {{ $submission->title }}<br/>
                                {!! nl2br($submission->description) !!}
                                </span>
    </p>
    @php
        $sub_attachments = isset($submission) ? $submission->attachments : [];
    @endphp

    @if($assignment->rearrangement == 1 && !is_null($assignment->rearrangement_type) && $assignment->rearrangement_type == 'student')
<!-- SUGGESTION -->
    @if($groups->count() > 0)
        @foreach($groups as $grp)
            <div class="box-ededed clearfix">
                <p class="head clearfix">Suggested By {{$submission->suggestions->first()->teacher->full_name}}</p>
                <div id="imageListId">
                    @foreach($grp as $item)
                        @if(
                        (!is_null($item->position) && $item->position != 0) &&
                        isset($item->media) && !$item->media->isEmpty())

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
                            </div>

                        @endif

                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
<!-- END SUGGESTION -->
@endif

    <ul class="vertical-list clearfix">

        @foreach($sub_attachments as $sAttachment)

            @if(count($sAttachment->comments) > 0)
                <li>
                    <p class="assign-content clearfix">
                                            <span>
                                                {{ $sAttachment->title }}<br/>
                                            {!! nl2br($sAttachment->full_text) !!}
                                            </span>

                        @if(isset($sAttachment->media) && !$sAttachment->media->isEmpty())
                            @foreach($sAttachment->media as $_media)
                                @if($_media->type == 'upload')
                                    <a href="{{$_media->url}}" target="_blank"><img width="200px" src="{{asset('assets_new/images/play-button.png')}}" alt="" /></a>
                                @elseif(str_contains($_media->type,'image'))
                                    <a id="gridPhotoImg" href="#" data-toggle="modal" data-target="#Photos"><img width="200px" src="{{ asset('storage/uploads/'.$_media->name) }}" alt="" /></a>
                                @elseif(str_contains($_media->type,'youtube'))
                                    <a href="https://www.youtube.com/embed/{{$_media->url}}" target="_blank"><img width="200px" src="https://img.youtube.com/vi/{{$_media->url}}/0.jpg" alt="" /></a>

                                @elseif(str_contains($_media->type,'vimeo'))
                                    <a href="https://player.vimeo.com/video/{{$_media->url}}" target="_blank"><img width="200px" src="https://i.vimeocdn.com/video/{{$_media->url}}/0.jpg" alt="" /></a>

                                @endif
                            @endforeach
                        @endif
                    </p>
                    <!-- Comment -->
                    @foreach($sAttachment->comments as $comm)
                        <p class="assign-content clearfix">
                            Critique by {{ $comm->user->full_name }}<br /><br />
                            {!! nl2br($comm->content) !!}

                            @if(isset($comm->media) && !$comm->media->isEmpty())
                                <br/>
                                @foreach($comm->media as $_media)
                                    @if($_media->type == 'upload')
                                        <a href="{{$_media->url}}" target="_blank"><img width="100px" src="{{asset('assets_new/images/play-button.png')}}" alt="" /></a>
                                    @elseif(str_contains($_media->type,'image'))
                                        <a id="gridPhotoImg" href="#" data-toggle="modal" data-target="#Photos"><img width="100px" src="{{ asset('storage/uploads/'.$_media->name) }}" alt="" /></a>
                                    @elseif(str_contains($_media->type,'youtube'))
                                        <a href="https://www.youtube.com/embed/{{$_media->url}}" target="_blank"><img width="100px" src="https://img.youtube.com/vi/{{$_media->url}}/0.jpg" alt="" /></a>

                                    @elseif(str_contains($_media->type,'vimeo'))
                                        <a href="https://player.vimeo.com/video/{{$_media->url}}" target="_blank"><img width="100px" src="https://i.vimeocdn.com/video/{{$_media->url}}/0.jpg" alt="" /></a>

                                    @endif
                                @endforeach
                            @endif
                        </p>
                        <a href="{{route('submission.all.critique',['assignment_id' => $assignment->id, 'submission_id' => $submission->id, 'attachment_id' => $sAttachment->id])}}" class="btn btn-primary btn-padding btn-sm mb-15">Respond to this Critique</a>
                    @endforeach
                </li>
            @endif

        @endforeach

    </ul>
</div>