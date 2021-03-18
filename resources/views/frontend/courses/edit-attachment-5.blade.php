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
                    <h1>Attachment Details</h1>
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
            @include('includes.partials.messages')
            <form action="{{route('submission.attachment.update',['assignment_id'=> $assignment->id, 'submission_id'=>$submission->id, 'id'=> $attachment->id])}}" method="POST" enctype="multipart/form-data" role="form" id="edit-assignment">
                {{ csrf_field() }}

                    <div class="row clearfix">
                        <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 form-group">
                            <input type="text" name="title_attach" id="title_attach" class="form-control" placeholder="Title" value="{{$attachment->title}}" />
                        </div>
                        <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 form-group">
                            <input type="text" name="metaData" id="metaData" class="form-control" placeholder="Meta Data" value="{{$attachment->meta_title}}" />
                        </div>
                        <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 form-group">
                            <input type="text" name="vimeoVideo" id="vimeoVideo" class="form-control" placeholder="Vimeo Video ID" value="{{$attachment->vimeo_id}}" />
                        </div>
                        <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 form-group">
                            <input type="text" name="youtubeVideo" id="youtubeVideo" class="form-control" placeholder="Youtube Video ID" value="{{$attachment->youtube_id}}" />
                        </div>
                        <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 form-group">
                            <input type="text" name="position" id="position" class="form-control" placeholder="Position" value="{{$attachment->position}}" />
                        </div>
                    </div>
                    <div class="row clearfix">
                        @if(isset($attachment->media) && !$attachment->media->isEmpty())
                                @foreach($attachment->media as $_media)
                                @if($_media->type == 'upload')
                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-group">
                                        <label>Uploaded Video
                                            <a href="{{$_media->url}}" target="_blank"><img src="{{asset('assets_new/images/play-button.png')}}" alt="" width="100"/></a>
                                        </label>
                                    </div>
                                @elseif(str_contains($_media->type,'image'))
                                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-group">
                                        <label>Uploaded File
                                        <img src="{{ asset('storage/uploads/'.$_media->name) }}" alt="" width="100"/>
                                        </label>
                                    </div>
                                    @endif
                                @endforeach
                        @endif
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                            <textarea class="form-control" name="description_attach" id="description" placeholder="Description" rows="3">{{$attachment->full_text}}</textarea>
                        </div>
                    </div>
                    <input type="submit" name="submitBtn" id="submitBtn" class="btn btn-primary br-24 btn-padding" value="UPDATE" />

                </form>
        </div>
    </section>
    <!-- End of course details section
        ============================================= -->
@endsection

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script>
        $(document).ready(function () {

        });


    </script>

@endpush