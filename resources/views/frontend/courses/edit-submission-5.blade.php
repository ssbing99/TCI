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
            <form action="{{route('submission.update',['assignment_id' => $assignment->id, 'submission_id' => $submission->id])}}" method="POST" enctype="multipart/form-data" role="form" id="edit-submission">
                {{ csrf_field() }}
                <div class="row clearfix">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                        <label>Title<span>*</span>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Project Statement" value="{{$submission->title}}" />
                        </label>
                    </div>
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 form-group">
                        <label>Description<span>*</span>
                            <textarea class="form-control" name="description" id="description" placeholder="Description" rows="3" >{!! $submission->description!!}</textarea>
                        </label>
                    </div>
                </div>
                    <input type="submit" name="submit" id="submit" class="btn btn-primary br-24 btn-padding" value="UPDATE" />

                </form>
        </div>
    </section>
    <!-- End of course details section
        ============================================= -->
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