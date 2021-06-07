@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )

@push('after-styles')
    <style>
     .listing-filter-form select{
            height:50px!important;
        }

        .page-item.active .page-link {
            color: #a1ca00;
            background-color: inherit;
            border-color: inherit;
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
                    <h1>New Critique Response</h1>
                </div>
            </div>
        </div>
    </header>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of course section
        ============================================= -->
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <p class="assign-content clearfix">
                    @foreach($attachment->comments as $comm)
                        <p class="assign-content clearfix">
                            <span>Critique by {{ $comm->user->full_name }}</span>
                            {!! nl2br($comm->content) !!}<br />

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
                        @endforeach
                    </p>

                    <form class="mtb-30" action="{{route('submission.critique',['assignment_id'=> $assignment->id, 'submission_id'=>$submission->id, 'attachment_id' => $attachment->id])}}" method="POST" data-lead="Residential">
                        @csrf
                        <input type="hidden" name="rating" id="rating">

                        <textarea class="form-control custom-input mb-15  @if($errors->has('critique')) border-bottom border-danger @endif" name="critique" id="textarea" rows="3" placeholder="Enter Text"></textarea>
                        @if($errors->has('critique'))
                            <span class="help-block text-danger">{{ $errors->first('critique', ':message') }}</span>
                        @endif

                        <button type="submit" name="submit" id="submit" class="btn btn-primary br-24 btn-padding btn-lg" value="Submit">CREATE</button>
                    </form>
                        <br />
                        <h4><a href="{{route('submission.show', $assignment->id)}}" class="btn btn-primary btn-padding br-24">Back</a></h4>
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
    <!-- End of course section
        ============================================= -->

@endsection

@push('after-scripts')
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