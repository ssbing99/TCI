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
                    <h1>Submission</h1>
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
            <form action="{{route('submission.store',[$assignment->id])}}" method="POST" enctype="multipart/form-data" role="form" id="edit-assignment">
                {{ csrf_field() }}
                <div class="row clearfix">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                        <label>Title<span>*</span>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Project Statement" />
                        </label>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                        <label>Description<span>*</span>
                            <textarea class="form-control" name="description" id="description" placeholder="Description" rows="3"></textarea>
                        </label>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                        <h3>Attachment Details</h3>
                    </div>
                </div>

                    <div class="row clearfix">
                        <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 form-group">
                            <input type="text" name="title_attach" id="title" class="form-control" placeholder="Title" />
                        </div>
                        <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 form-group">
                            <input type="text" name="metaData" id="metaData" class="form-control" placeholder="Meta Data" />
                        </div>
                        <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 form-group">
                            <input type="text" name="vimeoVideo" id="vimeoVideo" class="form-control" placeholder="Vimeo Video ID" />
                        </div>
                        <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 form-group">
                            <input type="text" name="youtubeVideo" id="youtubeVideo" class="form-control" placeholder="Youtube Video ID" />
                        </div>
                        <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 form-group">
                            <input type="text" name="position" id="position" class="form-control" placeholder="Position" />
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-group">
                            <label>Upload a Video
                                <input type="file" name="video_file" id="video_file" class="form-control" accept="video/avi,video/mpeg,video/quicktime,video/mp4"/>
                                <small class="text-muted">*Video must not more than 5 MB. Only accept MP4, MPEG, AVI type.</small>
                            </label>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-group">
                            <label>Upload a Photo File
                                <input type="file" name="attachment_file[]" id="attachment_file" class="form-control" accept="image/jpeg" multiple/>
                                <small class="text-muted">*Photos must be more than 500 px. on both horizontal and vertical dimensions. Only JPEG photo type.</small>
                            </label>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                            <textarea class="form-control" name="description_attach" id="description" placeholder="Description" rows="3"></textarea>
                        </div>
                    </div>
                <input type="button" name="submitBtn" id="submitBtn" class="btn btn-primary br-24 btn-padding" value="CREATE"  onclick="onSubmit(this.form)"/>

                </form>
        </div>
    </section>
    <!-- End of course details section
        ============================================= -->
@endsection

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script>

        function onSubmit(thisform){
            var vfile = thisform.video_file.files[0];
            var file = thisform.attachment_file.files[0];
            var msg = '';

            if(vfile){
                var vfsize = vfile.size / 1024;
                if(vfsize > (5 * 1024)){
                    msg = 'Video file too big, cannot more than 5MB. '
                }
            }

            if(file){
                var ffile = file.size / 1024;
                if(ffile > (5 * 1024)){
                    msg += '\nFile too big, cannot more than 5MB. '
                }
            }

            if(msg != ''){
                alert(msg);
            }else{
                thisform.submit();
            }


        }
        $(document).ready(function () {
        });


    </script>

@endpush