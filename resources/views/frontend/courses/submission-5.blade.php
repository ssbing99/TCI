@extends('frontend.layouts.app'.config('theme_layout'))

@push('after-styles')
@endpush

@section('content')
    <!-- Start of breadcrumb section
            ============================================= -->
    <header>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <h1>{{$lesson->course->title}}</h1>
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
                @if(session()->has('success'))
                <div class="col-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                    <div class="alert alert-dismissable alert-success fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{session('success')}}
                    </div>
                </div>
                @endif
                <div class="col-12 col-sm-8 col-md-8 col-lg-8 col-xl-8">
                    <p class="assign-content clearfix">
                        <span class="bold">Your Photo or Video Assignment Submission</span>
                        Next you must :
                    </p>
                    <ul class="sidelinks clearfix">
                        <li>Attach your photos or videos : Upload the first, adding additional ones using the “Add an attachment to your submission” button below. Photo should not exceed 500 px on the long side and Video should not exceed 150 mb in size.</li>
                    </ul>
                    <p class="assign-content clearfix">You may also :</p>
                    <ul class="sidelinks clearfix">
                        <li>Edit your Submission</li>
                        <li>Sequence your Photo's</li>
                    </ul>
                    <p class="assign-content clearfix">
                        Now - you must SUBMIT your assignment for instructor critique and comments. VERY IMPORTANT: If you do not "submit" your assignment, your instructor will not receive notification that you have completed it.<br /><br />
                        * Note that you may always return to submit additional photos or videos, as well as edit and sequence you assignment work.
                    </p>
                    <!-- <div class="btn-group btn-group-sm mb-15" role="group" aria-label="Basic example">
                      <button type="button" class="btn btn-outline-secondary">Add an attachment to your Submission</button>
                      <button type="button" class="btn btn-outline-secondary">Sequence your Images</button>
                      <button type="button" class="btn btn-outline-secondary">Edit Assignment Submission</button>
                    </div> -->

                    @if(isset($submission))
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
                                                        <img width="200px" src="{{asset('assets_new/images/play-button.png')}}" alt="" />
                                                    @elseif(str_contains($_media->type,'image'))
                                                        <img width="200px" src="{{ asset('storage/uploads/'.$_media->name) }}" alt="" />
                                                    @elseif(str_contains($_media->type,'youtube'))
                                                        <img width="200px" src="https://img.youtube.com/vi/{{$_media->url}}/0.jpg" alt="" />

                                                    @elseif(str_contains($_media->type,'vimeo'))
                                                        <img width="200px" src="https://i.vimeocdn.com/video/{{$_media->url}}/0.jpg" alt="" />

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
                                                        <img width="100px" src="{{asset('assets_new/images/play-button.png')}}" alt="" />
                                                    @elseif(str_contains($_media->type,'image'))
                                                        <img width="100px" src="{{ asset('storage/uploads/'.$_media->name) }}" alt="" />
                                                    @elseif(str_contains($_media->type,'youtube'))
                                                        <img width="100px" src="https://img.youtube.com/vi/{{$_media->url}}/0.jpg" alt="" />

                                                    @elseif(str_contains($_media->type,'vimeo'))
                                                        <img width="100px" src="https://i.vimeocdn.com/video/{{$_media->url}}/0.jpg" alt="" />

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
                    @endif
                </div>
                <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    @if(isset($submission))
                    <a href="{{route('submission.attachment.create', ['assignment_id' => $assignment->id, 'submission_id' => $submission->id])}}" class="btn btn-primary br-24 btn-paddiing btn-block mb-15">Add an attachment</a>
                    <a href="{{ route('submission.attachment.sequence', ['assignment_id' => $assignment->id, 'submission_id' => $submission->id]) }}" class="btn btn-primary br-24 btn-paddiing btn-block mb-15">Sequence your Images</a>
                    <a href="{{ route('submission.edit', ['assignment_id' => $assignment->id, 'submission_id' => $submission->id]) }}" class="btn btn-primary br-24 btn-paddiing btn-block mb-15">Edit Assignment Submission</a>
                    @else
                        <a href="#" class="btn btn-primary br-24 btn-paddiing btn-block mb-15">Add an attachment</a>
                        <a href="#" class="btn btn-primary br-24 btn-paddiing btn-block mb-15">Sequence your Images</a>
                        <a href="#" class="btn btn-primary br-24 btn-paddiing btn-block mb-15">Edit Assignment Submission</a>
                    @endif
                    <div class="side-bg clearfix">
                        <div class="side-title clearfix">Course Instructor</div>
                        @foreach($course->teachers as $key=>$teacher)
                            @php $key++ @endphp
                            <div class="side-pic clearfix">
                                <img src="{{$teacher->picture}}" alt="" />
                                <p>{{$teacher->full_name}}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="side-bg clearfix">
                        <div class="side-title clearfix">Submit your Assignment</div>
                        @if(isset($submission))
                            <p class="assign-txt clearfix">You have submitted your assignment to your instructor to critique.</p>
                        @else
                            <p class="assign-txt clearfix"><a href="{{route('submission.create', $assignment->id)}}">Create your first submission</a></p>
                        @endif
                    </div>
                    @php
                        $sub_attachments = isset($submission) ? $submission->attachments : [];
                    @endphp
                    <div class="side-bg clearfix">
                        <div class="side-title clearfix">Photo's</div>
                        <div class="photos clearfix">
                            @foreach($sub_attachments as $item)
                                @if(isset($item->media) && !$item->media->isEmpty())
                                    @foreach($item->media as $_media)
                                        @if($_media->type == 'upload')
                                            <a href="#" data-toggle="modal" data-target="#gridPhotos"><img src="{{asset('assets_new/images/play-button.png')}}" alt="" /></a>
                                        @elseif(str_contains($_media->type,'image'))
                                            <a href="#" data-toggle="modal" data-target="#gridPhotos"><img src="{{ asset('storage/uploads/'.$_media->name) }}" alt="" /></a>
                                        @elseif(str_contains($_media->type,'youtube'))
                                            <a href="#" data-toggle="modal" data-target="#gridPhotos"><img src="https://img.youtube.com/vi/{{$_media->url}}/0.jpg" alt="" /></a>

                                        @elseif(str_contains($_media->type,'vimeo'))
                                            <a href="#" data-toggle="modal" data-target="#gridPhotos"><img src="https://i.vimeocdn.com/video/{{$_media->url}}/0.jpg" alt="" /></a>

                                        @endif
                                    @endforeach


                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End of course details section
        ============================================= -->
    <!-- Modal -->
    <div class="modal fade" id="gridPhotos" tabindex="-1" role="dialog" aria-labelledby="photos" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="photos clearfix">
                        @foreach($sub_attachments as $item)
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
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="Photos" tabindex="-1" role="dialog" aria-labelledby="photos" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <a class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <img id="big-photo" src="" class="img-full" alt="" />
                </div>
            </div>
        </div>
    </div>
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