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
                        @if(count($submission->critiques) > 0)
                            @foreach($submission->critiquesById(auth()->user()->id)->get() as $item)
                                <span>Critique by {{ $item->user->full_name }}</span>
                                {!! nl2br($item->content) !!}<br /><br />
                            @endforeach
                        @endif
                    </p>
                    <form class="mtb-30" action="{{route('submission.critique',['assignment_id'=> $assignment->id, 'submission_id'=>$submission->id])}}" method="POST" data-lead="Residential">
                        @csrf
                        <input type="hidden" name="rating" id="rating">

                        <textarea class="form-control custom-input mb-15  @if($errors->has('critique')) border-bottom border-danger @endif" name="critique" id="textarea" rows="3" placeholder="Enter Text"></textarea>
                        @if($errors->has('critique'))
                            <span class="help-block text-danger">{{ $errors->first('critique', ':message') }}</span>
                        @endif

                        <button type="submit" name="submit" id="submit" class="btn btn-primary br-24 btn-padding btn-lg" value="Submit">CREATE</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- End of course section
        ============================================= -->

@endsection

@push('after-scripts')
    <script>
        $(document).ready(function () {
        });

    </script>
@endpush