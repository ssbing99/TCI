@extends('frontend.layouts.app'.config('theme_layout'))

@push('after-styles')
    <style>
        .attachment img {
            max-width: 200px;
            max-height: 200px;
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
                    <h1>Assignment {{$assignment->title}}</h1>
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
            <div class="row clearfix">
                <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="border-box clearfix">
                        <div class="flexbox clearfix">
                            <img src="{{$submission->user->picture}}" alt="" />
                            <div class="flexcontent clearfix">
                                <div class="student-name clearfix">
                                    <div class="top">Created by</div>
                                    {{$submission->user->full_name}}
                                    <div class="bottom">on {{ $submission->created_at->format('M d, Y, H:i') }} - Marked as complete - {{$submission->attachments->count()}} attachments</div>
                                </div>
                                {!! nl2br($submission->description) !!}
                            </div>
                        </div>
                    </div>

                    <form action="{{route('student.submission.critique',['assignment_id'=> $assignment->id, 'submission_id'=>$submission->id])}}" method="POST" enctype="multipart/form-data" role="form" id="edit-assignment">
                        {{ csrf_field() }}
                    @php
                        $sub_attachments = isset($submission) ? $submission->attachments : [];
                        $all_attachments = isset($submission) ? $submission->attachmentsOrderById : [];
                        $attch_cnt = 1;
                    @endphp

                        <div id="imageListId">
                            @foreach($sub_attachments as $item)
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

{{--                        <a href="{{ route('submission.attachment.suggest.sequence', ['assignment_id' => $assignment->id, 'submission_id' => $submission->id, 'groupId' => $sub_attachments->first()->a_group_id]) }}" class="btn btn-primary btn-padding br-24 mb-2">Suggest New Sequence</a>--}}

                        @if($assignment->rearrangement == 0 || ( $assignment->rearrangement == 1 && !is_null($assignment->rearrangement_type) && $assignment->rearrangement_type == 'student'))
                            <a href="{{ route('submission.attachment.suggest.sequence', ['assignment_id' => $assignment->id, 'submission_id' => $submission->id, 'groupId' => '0']) }}" class="btn btn-primary btn-padding br-24 mb-2">Suggest New Sequence</a>
                        @elseif($assignment->rearrangement == 1 && !is_null($assignment->rearrangement_type) && $assignment->rearrangement_type == 'admin')
                            <a href="{{ route('submission.attachment.suggest.sequence', ['assignment_id' => $assignment->id, 'submission_id' => $submission->id, 'groupId' => $sub_attachments->first()->a_group_id]) }}" class="btn btn-primary btn-padding br-24 mb-2">Suggest New Sequence</a>
                        @endif

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

                        @if($all_attachments->first() !== null )
                        <div class="box-ededed clearfix">
                            <p class="head clearfix">Comments</p>
                            @if(count($all_attachments->first()->comments) > 0)

                                @foreach($all_attachments->first()->comments as $item)
                                    <div class="flexbox clearfix mb-5">
                                        <img src="{{$item->user->picture}}" alt="" />
                                        <div class="flexcontent clearfix">
                                            <div class="student-name clearfix">Critique by {{$item->user->full_name}}<div class="bottom">September 14, 2020</div></div>
                                            <p class="assign-content clearfix">{!! nl2br($item->content) !!}</p>
                                            @if(isset($item->media) && !$item->media->isEmpty())
                                                @foreach($item->media as $_media)
                                                    @if($_media->type == 'upload')
                                                        <a href="{{$_media->url}}" target="_blank"><img src="{{asset('assets_new/images/play-button.png')}}" alt="" /></a>
                                                    @elseif(str_contains($_media->type,'image'))
                                                        <a id="gridPhotoImg" href="#" data-toggle="modal" data-target="#Photos"><img src="{{ asset('storage/uploads/'.$_media->name) }}" alt="" /></a>
                                                    @elseif(str_contains($_media->type,'youtube'))
                                                        <a href="https://www.youtube.com/embed/{{$_media->url}}" target="_blank"><img src="https://img.youtube.com/vi/{{$_media->url}}/0.jpg" alt="" /></a>

                                                    @elseif(str_contains($_media->type,'vimeo'))
                                                        <a href="https://player.vimeo.com/video/{{$_media->url}}" target="_blank"><img src="https://i.vimeocdn.com/video/{{$_media->url}}/0.jpg" alt="" /></a>

                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endforeach

                            @endif
                            <div class="line clearfix"></div>
                            <div class="student-name clearfix">
                                Critique
                                <div class="bottom">Upload an image :</div>
                            </div>
                            {{--                            <form action="{{route('submission.critique',['assignment_id'=> $assignment->id, 'submission_id'=>$submission->id])}}" method="POST" enctype="multipart/form-data" role="form" id="edit-assignment">--}}

                            <input type="file" name="file_{{$all_attachments->first()->id}}" id="file" />

                            <p class="assign-content clearfix">Content</p>

                            <textarea class="form-control custom-input mb-15  @if($errors->has('critique_'.$all_attachments->first()->id)) border-bottom border-danger @endif" name="critique_{{$all_attachments->first()->id}}" id="textarea" rows="5" placeholder="Enter Text"></textarea>
                            @if($errors->has('critique_'.$all_attachments->first()->id))
                                <span class="help-block text-danger">{{ $errors->first('critique_'.$all_attachments->first()->id, ':message') }}</span>
                            @endif

                            {{--                            </form>--}}
                        </div>

                    <input type="button" name="submitBtn" id="submitBtn" class="btn btn-primary br-24 btn-padding" value="Save Changes"  onclick="this.form.submit()"/>

                            @endif
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="Photos" tabindex="-1" role="dialog" aria-labelledby="photos" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <img id="big-photo" src="images/pic-full-1.jpg" class="img-full" alt="" />
                </div>
            </div>
        </div>
    </div>
    <!-- End of course details section
        ============================================= -->
@endsection

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script>

        $(document).ready(function () {
            $(document).on('click', '#gridPhotoImg', function () {
                var imgSrc = $(this).children('img')[0].src;

                if(imgSrc){
                    $('#big-photo').attr('src',imgSrc);
                    $('Photos').modal('show');
                }
            });
        });

    </script>

@endpush