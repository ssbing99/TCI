@extends('frontend.layouts.app'.config('theme_layout'))

@section('title', ($course->meta_title) ? $course->meta_title : app_name() )
@section('meta_description', $course->meta_description)
@section('meta_keywords', $course->meta_keywords)

@push('after-styles')
    <style>
        .leanth-course.go {
            right: 0;
        }
        .video-container iframe{
            max-width: 100%;
        }

    </style>
    <link rel="stylesheet" href="https://cdn.plyr.io/3.5.3/plyr.css"/>

@endpush

@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    <header>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <h1>Add Review for {{$course->title}}</h1>
                </div>
            </div>
        </div>
    </header>
    <!-- End of breadcrumb section
        ============================================= -->

    <!-- Start of course details section
        ============================================= -->
{{--    @if(isset($review) || ($is_reviewed == false))--}}
        @php
            if(isset($review)){
                $route = route('courses.review.update',['id'=>$review->id]);
            }else{
               $route = route('courses.review',['id'=> $course->id]);
            }
        @endphp
{{--    @endif--}}
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    @if(session()->has('success'))
                        <div class="alert alert-dismissable alert-success fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{session('success')}}
                        </div>
                    @endif
                    <p class="assign-content clearfix">
                        <span>Review</span>
                    </p>
                    <form class="mtb-30" action="{{$route}}" method="POST" data-lead="Residential">
                        @csrf
                    <textarea class="form-control custom-input mb-15 @if($errors->has('review')) border-bottom border-danger @endif" name="review" id="review" rows="3" placeholder="Enter Text">@if(isset($review)){{$review->content}} @endif</textarea>
                        @if($errors->has('review'))
                        <span class="help-block text-danger">{{ $errors->first('review', ':message') }}</span><br/>
                        @endif
                        <button type="submit" name="submit" id="submit" class="btn btn-primary br-24 btn-padding btn-lg" value="Submit">CREATE</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- End of course details section
        ============================================= -->

    <!-- Start of BUNDLE course details section
        ============================================= -->

    <!-- End of BUNDLE course details section
        ============================================= -->

@endsection

@push('after-scripts')
    <script src="https://cdn.plyr.io/3.5.3/plyr.polyfilled.js"></script>

    <script>
        const player = new Plyr('#player');

        $(document).on('change', 'input[name="stars"]', function () {
            $('#rating').val($(this).val());
        })
                @if(isset($review))
        var rating = "{{$review->rating}}";
        $('input[value="' + rating + '"]').prop("checked", true);
        $('#rating').val(rating);
        @endif
    </script>
@endpush
