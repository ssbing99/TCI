@extends('frontend.layouts.app'.config('theme_layout'))

@push('after-styles')
    <link rel="stylesheet" href="https://cdn.plyr.io/3.5.3/plyr.css"/>
    <link href="{{asset('plugins/touchpdf-master/jquery.touchPDF.css')}}" rel="stylesheet">

    <style>
    </style>
@endpush

@section('content')
    <!-- Start of breadcrumb section
            ============================================= -->
    <header>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <h1>My Assignments</h1>
                </div>
            </div>
        </div>
    </header>
    <!-- End of breadcrumb section
        ============================================= -->

    <!-- Start of course details section
            ============================================= -->
    <section class="nopadding clearfix">
        <div class="container">
            <div class="assign-top clearfix">
                <div class="row clearfix">
                    <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                        <div class="assign-title clearfix">Course Instructor</div>
                        @foreach($course->teachers as $key=>$teacher)
                            <div class="assign-img clearfix">
                                <img src="{{$teacher->picture}}" alt="" /> {{$teacher->full_name}}
                            </div>
                        @endforeach

                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                        <div class="assign-title clearfix">Your Submission</div>
                        @if($assignment->submissionsById(auth()->user()->id)->count() > 0)
                            <p class="assign-txt clearfix"><span><a href="{{route('submission.show', $assignment->id)}}">{{$assignment->submissionsById(auth()->user()->id)->first()->title}}</a></span>Created over {{$assignment->created_at->diffforhumans()}}</p>
                        @else
                            <p class="assign-txt clearfix"><span><a href="{{route('submission.create', $assignment->id)}}">Create your submission</a></span></p>
                        @endif

                    </div>
                    <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                        <div class="assign-title clearfix">Other Submission</div>
                        @if($otherSubmission->count() > 0)
                            <p class="assign-txt clearfix">View Submissions by Fellow Students<span><a>{{$otherSubmission->first()->title}}</a></span>Created about 2 months ago by {{$otherSubmission->first()->user->full_name}}</p>
                        @else
                            <p class="assign-txt clearfix">There are no other submissions for this assignment.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <p class="assign-content clearfix">
                        <span class="bold">{{$assignment->title}}<a href="#" onclick="generateAssignmentPdf({{$assignment->id}})"  class="btn btn-primary btn-padding br-24 float-right">Generate Pdf</a></span>
                        <span>Assignment for lesson '<a href="{{route('lessons.show',['course_id' => $course->id,'slug'=>$lesson->slug])}}">{{$lesson->title}}</a>'</span>
                        {!! $assignment->full_text !!}
                    </p>

                    <!-- COmment -->
                    @if(count($assignment->comments) > 0)

                        @foreach($assignment->comments as $item)
                            <div class="discuss clearfix">
                                <div class="discuss-head clearfix">
                                    <img src="{{$item->user->picture}}" alt="" />
                                    <p>{{$item->user->full_name}}<span>{{$item->created_at->diffforhumans()}}</span></p>
                                </div>
                                <div class="discuss-box clearfix">
                                    @if(isset($item->media) && !$item->media->isEmpty())
                                        @foreach($item->media as $_media)
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
                                    <p class="discuss-txt clearfix">
                                        {!! nl2br($item->content) !!}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <form class="mtb-30" action="{{route('assignment.comment',['id'=> $assignment->id])}}" method="POST" enctype="multipart/form-data" data-lead="Residential">
                        @csrf
                        <input type="hidden" name="rating" id="rating">

                        <div class="row clearfix">
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-group">
                            <label>Upload a Video
                                <input type="file" name="video_file" id="video_file" class="form-control" accept="video/avi,video/mpeg,video/quicktime,video/mp4"/>
                                <small class="text-muted">*Video must not more than 5 MB. Only accept MP4, MPEG, AVI type.</small>
                            </label>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-group">
                            <label>Upload a Photo File
                                <input type="file" name="attachment_file" id="attachment_file" class="form-control" accept="image/jpeg" />
                                <small class="text-muted">*Photos must be more than 500 px. on both horizontal and vertical dimensions. Only JPEG photo type.</small>
                            </label>
                        </div>

                        <textarea class="form-control custom-input mb-15  @if($errors->has('comment')) border-bottom border-danger @endif" name="comment" id="textarea" rows="3" placeholder="Enter Text"></textarea>
                        @if($errors->has('comment'))
                            <span class="help-block text-danger">{{ $errors->first('comment', ':message') }}</span>
                        @endif

                        </div>
                        <button type="submit" name="submit" id="submit" class="btn btn-primary br-24 btn-padding btn-lg" value="Submit">CREATE</button>
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
                    <img id="big-photo" src="" class="img-full" alt="" />
                </div>
            </div>
        </div>
    </div>
    <!-- End of course details section
        ============================================= -->
    <form id="genPdfForm" name="genPdfForm" target="_blank" method="post" action="{{route('generate.pdf')}}" role="form">
        @csrf
        <input type="hidden" id="pdf_lesson_id" name="pdf_lesson_id" value=""/>
        <input type="hidden" id="pdf_assignment_id" name="pdf_assignment_id" value=""/>
    </form>

@endsection

@push('after-scripts')
    <script src="{{asset('plugins/sticky-kit/sticky-kit.js')}}"></script>
    <script src="https://cdn.plyr.io/3.5.3/plyr.polyfilled.js"></script>
    <script src="{{asset('plugins/touchpdf-master/pdf.compatibility.js')}}"></script>
    <script src="{{asset('plugins/touchpdf-master/pdf.js')}}"></script>
    <script src="{{asset('plugins/touchpdf-master/jquery.touchSwipe.js')}}"></script>
    <script src="{{asset('plugins/touchpdf-master/jquery.touchPDF.js')}}"></script>
    <script src="{{asset('plugins/touchpdf-master/jquery.panzoom.js')}}"></script>
    <script src="{{asset('plugins/touchpdf-master/jquery.mousewheel.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script>

        function generateAssignmentPdf(id){
            var thisform = document.forms.genPdfForm;
            thisform.pdf_lesson_id.value = '';
            thisform.pdf_assignment_id.value = id;
            thisform.submit();
        }

        $(document).ready(function () {
            var uploadField = $('input[type="file"]');

            $(document).on('click', '#gridPhotoImg', function () {
                var imgSrc = $(this).children('img')[0].src;

                if(imgSrc){
                    $('#big-photo').attr('src',imgSrc);
                    $('Photos').modal('show');
                }
            });

            $(document).on('change', 'input[name="video_file"]', function () {

                var $this = $(this);
                $(this.files).each(function (key, value) {
                    if (value.size > 5000000) {
                        alert('"' + value.name + '"' + 'exceeds limit of maximum file upload size')
                        $this.val("");
                    }
                })
            });
            $(document).on('change', 'input[name="attachment_file"]', function () {
                var $this = $(this);
                $(this.files).each(function (key, value) {
                    if (value.size > 5000000) {
                        alert('"' + value.name + '"' + 'exceeds limit of maximum file upload size')
                        $this.val("");
                    }
                })
            });

        });

    </script>

@endpush