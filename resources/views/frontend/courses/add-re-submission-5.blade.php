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
            <form action="{{route('submission.store.rearrangement',[$assignment->id])}}" method="POST" enctype="multipart/form-data" role="form" id="edit-assignment">
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
                @if(isset($attachments))
                    @include('frontend.courses.partials.submission-sequence-form')
                @endif
                <input type="button" name="submitBtn" id="submitBtn" class="btn btn-primary br-24 btn-padding" value="CREATE"  onclick="seqFormSubmit(this.form)"/>

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