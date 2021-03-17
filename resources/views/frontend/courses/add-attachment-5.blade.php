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
            <form action="{{route('submission.attachment.store',['assignment_id'=> $assignment->id, 'submission_id'=>$submission->id])}}" method="POST" enctype="multipart/form-data" role="form" id="edit-assignment">
                {{ csrf_field() }}

                    <div class="row clearfix">
                        <div class="col-12 col-sm-3 col-md-3 col-lg-3 col-xl-3 form-group">
                            <input type="text" name="title_attach" id="title_attach" class="form-control" placeholder="Title" />
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
                                <small class="text-muted">File to Upload</small>
                            </label>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 form-group">
                            <label>Upload a File
                                <input type="file" name="attachment_file" id="attachment_file" class="form-control" accept="image/*,.pdf" />
                                <small class="text-muted">*Photos must be more than 500 px. on both horizontal and vertical dimensions</small>
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
            // var vfile = thisform.video_file.files[0];
            // var file = thisform.attachment_file.files[0];
            // var msg = '';
            //
            // if(vfile){
            //     var vfsize = vfile.size / 1024;
            //     if(vfsize > (5 * 1024)){
            //         msg = 'Video file too big, cannot more than 5MB. '
            //     }
            // }
            //
            // if(file){
            //     var ffile = file.size / 1024;
            //     if(ffile > (5 * 1024)){
            //         msg += '\nFile too big, cannot more than 5MB. '
            //     }
            // }
            //
            // if(msg != ''){
            //     alert(msg);
            // }else{
                thisform.submit();
            // }


        }
        $(document).ready(function () {
            var uploadField = $('input[type="file"]');

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