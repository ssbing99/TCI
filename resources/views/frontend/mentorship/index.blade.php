@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )

@push('after-styles')
    <style>

        /*}*/
        .listing-filter-form select{
            height:50px!important;
        }

        /*ul.pagination {*/
        /*    display: inline;*/
        /*    text-align: center;*/
        /*}*/

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
                    <h1>The TCI Mentorship Program</h1>
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
                    <p class="course-txt clearfix">
                        <span>What is a mentor?</span>
                        Someone who champions your work and possesses the experience, expertise and empathy to guide you in a supportive and confident manner. A mentor is a working professional who has “been down that road” and is willing to lend one-on-one assistance to move you toward your desired goal as a photographer and visual storyteller.
                        <span>Who is a TCI mentorship meant for?</span>
                        TCI’s Mentorship Program is designed for keen amateurs and aspiring professionals who are passionate about their image-making and want to hone their craft and expand their vision. Whether the aim is publication, preparation for an exhibition, establishing that long-dreamed-of career or building a stronger, more impressive portfolio of work, mentorship can be an invaluable experience
                        <span>What can I expect to gain from mentorship?</span>
                        Under the watchful guidance of a TCI mentor, you can anticipate much quicker progress in your development - shorter time in reaching your goals. Expect confidence-building too, for along with progressive success comes the awareness of one’s own talent and the ability to apply it creativity on a consistent basis.
                        <span>How does the program work?</span>
                        Choose a 1-, 3-, or 6-month program of enrollment and select a participating TCI mentor of your choice. From here you will be assigned a mentorship webpage, through which all one-on-one interaction with your mentor will take place.<br /><br />
                        The first step in your program will be to provide background information on yourself as an image-maker, along with a description of the goal or associated goals you wish to reach in the course of your tuition.<br /><br />
                        Following this, you and your mentor will agree on a time to discuss your aims via a Skype consultation (approximately 30 minutes in length). With this information, a mentorship “path” will be established to include goal-oriented lessons and assignments, tailored to move you productively in the direction you want to go. In the course of a month, a second Skype consultation will take place to assess progress made and fine-tune your program for the remainder of your tuition.<br /><br />
                        Note: While your mentor will be available to guide and encourage your development with respect to your stated goal(s), it must be appreciated that each TCI mentor is also a working professional, with his or her own professional responsibilities. Therefore, all mentorship interaction should be treated with respect in terms of time commitment and privacy, as well as approached with a degree of flexibility.
                        <span>How do I decide which instructor to select as my mentor?</span>
                        The TCI mentor - student relationship is an important one. For it to be maximally productive, the “match” must be comfortable and dynamic. Consider the area of image-making you are interested in - photography, video production or multi-media. Read the profiles of participating instructors, working in those areas. Go to their personal websites and look through their work. When you feel you’ve found the TCI mentor that best fits your direction and needs, enroll on the length of program that suits you and indicate the mentor you would like to work with
                        <span>Can I change mentors if I want?</span>
                        Yes. If you find that your first mentorship choice is not exactly what you’re looking for, you may select another mentor. Simply place your request for change at support@thecompellingimage.com before the start of your second week of tuition.
                        <span>What if I want to stop my mentorship program completely?</span>
                        Not a problem. As long as your termination request is filed at support@thecompellingimage.com before the start of your program’s second week, you will receive your tuition immediately and in full.
                        <span>Questions</span>
                        Contact us at support@thecompellingimage.com or schedule Skype time to discuss your needs further.
                    </p>
                </div>
            </div>
            <form method="post" action="{{route('mentorship.enroll')}}" role="form">
                @csrf
                <div class="row clearfix">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <p class="course-txt clearfix"><span>Sign up for the mentorship program today!</span></p>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" href="/courses/101-one-month-mentorship/purchase/new" data-cl="1month">
                        <div class="bg-e91e63">1<br>Month<br>$199.00</div>
                        <div class="radio">
                            <label class="text-center">
                                <input type="radio" value="1" class="1month_" name="mentorship_id" checked="checked">
                                1 Month
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" href="/courses/103-three-month-mentorship/purchase/new" data-cl="3month">
                        <div class="bg-03a9f4">3<br>Month<br>$579.00</div>
                        <div class="radio">
                            <label class="text-center">
                                <input type="radio" value="3" class="3month_" name="mentorship_id">
                                3 Month
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12" href="/courses/102-six-month-mentorship/purchase/new" data-cl="6month">
                        <div class="bg-4caf50">6<br>Month<br>$979.00</div>
                        <div class="radio">
                            <label class="text-center">
                                <input type="radio" value="6" class="6month_" name="mentorship_id">
                                6 Month
                            </label>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 hidden-mobile"></div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-12">
                        <select class="form-control drp mt-15 1month" name="instructor_list" id="instructor_list_ids">
                            <option value="">- Select Mentor -</option>
                            @if(count($teachers) > 0)
                                @foreach($teachers as $item)
                                <option value="{{$item->id}}">{{$item->full_name}}</option>
                                @endforeach
                            @endif
                        </select>
                        <p style="color: red; display: none;" class="err">Please select instructor !!</p>
                        <input type="hidden" name="mentorship" id="price" value="">
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 hidden-mobile"></div>
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <center>
                            <button type="button" name="btnSubmit" class="btn btn-primary mtb-15" onclick="onSubmit(this.form)">SIGN UP</button>
                        </center>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- End of course section
        ============================================= -->

    <!-- Start recent testimonial section
        ============================================= -->
    @include('frontend.layouts.partials.custom_testimonial');
    <!-- End recent blogs section
        ============================================= -->

    <!-- Start recent blogs section
        ============================================= -->
    @include('frontend.layouts.partials.recent_blogs');
    <!-- End recent blogs section
        ============================================= -->



@endsection

@push('after-scripts')
    <script>
        function onSubmit(thisform){
            if(thisform.instructor_list.value == ""){
                alert('Please select a Mentor.')
            }else{
                thisform.submit();
            }
        }

        $(document).ready(function () {
        });

    </script>
@endpush