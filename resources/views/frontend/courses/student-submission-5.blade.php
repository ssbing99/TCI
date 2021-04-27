@extends('frontend.layouts.app'.config('theme_layout'))

@push('after-styles')
    <style>
        .attachment img {
            max-width: 200px;
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
{{--                                <p class="assign-content clearfix">--}}
{{--                                    The story I have chosen is called the Magdalene Project, located in Niagara Falls, NY.<br />--}}
{{--                                    The project addresses many social issues including homelessness, prostitution, drug use, support for women and children, and an outreach program dedicated to the poverty-stricken neighborhoods of Niagara Falls. Due to Covid-19, some of the programs have been put on hold, so I have chosen to concentrate on the outreach portion of the project.<br /><br />--}}
{{--                                    Through the use of photographs I would like to show that not only does the Magdalene Project&rsquo;s founder, Joanne Lorenzo, offer many services through her outreach, but how she also has a deep relationship with each individual person that she serves. At times it can be a spiritual relationship of praying and offering hope; at other times, it is by listening to ensure that their troubled voices are heard and understood.<br /><br />Attached are some images of Joanne at work and of the people she serves. I have an established rapport with Joanne and have photographed her and her team for over a year.--}}
{{--                                </p>--}}
                            </div>
                        </div>
                    </div>

                    <form action="{{route('student.submission.critique',['assignment_id'=> $assignment->id, 'submission_id'=>$submission->id])}}" method="POST" enctype="multipart/form-data" role="form" id="edit-assignment">
                        {{ csrf_field() }}
                    @php
                        $sub_attachments = isset($submission) ? $submission->attachments : [];
                        $attch_cnt = 1;
                    @endphp
                    @foreach($sub_attachments as $attachment)
                        <p class="head clearfix">Attachment {{$attch_cnt++}} of {{$sub_attachments->count()}}</p>
                        <div class="attachment clearfix">
                            @if(isset($attachment->media) && !$attachment->media->isEmpty())
                                @foreach($attachment->media as $_media)
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
                                    <div class="border-dashed">
                                        <p class="head clearfix">Description by {{$submission->user->full_name}}: {{$attachment->title}} <br/>{!! nl2br($attachment->full_text) !!}</p>
                                    </div>


                            @endif
                        </div>

                        <!-- COMMENT --><!-- Critique -->
                        <div class="box-ededed clearfix">
                            <p class="head clearfix">Comments</p>
                            @if(count($attachment->comments) > 0)

                                @foreach($attachment->comments as $item)
                                    <div class="flexbox clearfix">
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

                                <input type="file" name="file_{{$attachment->id}}" id="file" />

                                <p class="assign-content clearfix">Content</p>

                                <textarea class="form-control custom-input mb-15  @if($errors->has('critique_'.$attachment->id)) border-bottom border-danger @endif" name="critique_{{$attachment->id}}" id="textarea" rows="5" placeholder="Enter Text"></textarea>
                                @if($errors->has('critique_'.$attachment->id))
                                    <span class="help-block text-danger">{{ $errors->first('critique_'.$attachment->id, ':message') }}</span>
                                @endif

{{--                            </form>--}}
                        </div>

                    @endforeach

                    <input type="button" name="submitBtn" id="submitBtn" class="btn btn-primary br-24 btn-padding" value="Save Changes"  onclick="this.form.submit()"/>

                    </form>
                </div>
            </div>
        </div>
    </section>

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