@extends('frontend.layouts.app'.config('theme_layout'))
@push('after-styles')
    <style>
        .section-title-2 h2:after {
            background: #ffffff;
            bottom: 0px;
            position: relative;
        }

        .course-meta li {
            font-family: "Roboto-Regular", Arial;
            font-size: 14px;
            color: #777;
            text-decoration: none;
            text-align: center;
            line-height: 20px;
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
                    <h1>Workshop</h1>
                </div>
            </div>
        </div>
    </header>
    <!-- End of breadcrumb section
        ============================================= -->

    <!-- Start of teacher details area
        ============================================= -->
    <section>
        <div class="container">
            <div class="row clearfix">
                <div class="col-12 mb-3">
                    <div class="page-title clearfix">{{$workshop->title}}</div>
                    <hr />
                    <ul class="workshop-details clearfix">
                        <li>Date<div class="right">{{$workshop->workshop_date}}</div></li>
                        <li>Workshop Price<div class="right">{{$appCurrency['symbol']}} {{$workshop->price}}</div></li>
                        <li>Deposit<div class="right">{{$appCurrency['symbol']}} {{$workshop->deposit}}</div></li>
                    </ul>
                    <div class="workshopdetail-box clearfix">
                        <img src="{{$workshop->getFirstImage()}}" class="img-full" alt="" />
                        <span>
                @if(!auth()->check())
                 <div class="row clearfix">
                  <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 col-xxl-4">
                      <a class="btn btn-primary btn-block mb-15" onclick="openLoginWithSession('deposit')" href="#">Enroll on this Workshop(Pay Deposit)</a>
                  </div>
                  <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 col-xxl-4">
                      <a class="btn btn-primary btn-block mb-15" onclick="openLoginWithSession('balance')" href="#">Enroll on this Workshop(Pay Balance)</a>
                  </div>
                  <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 col-xxl-4">
                      <a class="btn btn-primary btn-block mb-15" onclick="openLoginWithSession('supplement')" href="#">Enroll on this Workshop(Pay Supplement)</a>
                  </div>
                </div>

                @elseif(auth()->check() && (auth()->user()->hasRole('student')))
                <div class="row clearfix">
                  <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 col-xxl-4">
                    <a href="{{route('workshops.enroll',['id'=>$workshop->id, 'type'=>'deposit'])}}" class="btn btn-primary btn-sm">Enroll on this Workshop(Pay Deposit)</a>
                  </div>
                  <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 col-xxl-4">
                    <a href="{{route('workshops.enroll',['id'=>$workshop->id, 'type'=>'balance'])}}" class="btn btn-primary btn-sm">Enroll on this Workshop(Pay Balance)</a>
                  </div>
                  <div class="col-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 col-xxl-4">
                    <a href="{{route('workshops.enroll',['id'=>$workshop->id, 'type'=>'supplement'])}}" class="btn btn-primary btn-sm">Enroll on this Workshop(Pay Supplement)</a>
                  </div>
                </div>
                @endif
              </span>
                    </div>
                </div>

                <div class="col-12">
                    {!! $workshop->description !!}

                    <div class="page-title clearfix">Instructor</div>

                    @foreach($workshop->teachers as $key=>$teacher)
                    <div class="workshop-instructor clearfix">
                        <img src="{{$teacher->picture}}" alt="" />
                        <div class="insname clearfix">{{$teacher->full_name}}<span><a href="{{route('teachers.show',['id'=>$teacher->id])}}" class="btn btn-primary">Read Profile</a></span></div>
                    </div>
                    @endforeach

                    {!! $workshop->enrolment_details !!}
                </div>
            </div>
        </div>
    </section>
    <!-- End  of teacher details area
        ============================================= -->
@endsection

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>

    <script>

        function openLoginWithSession(type){
            //clean
            $('#enrollId').val('');
            $('#isGift').val('false');

            $('#workshopId').val('{{$workshop->id}}');
            $('#workshopType').val(type);
            Cookies.set('withWorkshop', '{"workshopId": "{{$workshop->id}}", "type": "'+type+'"}');
            $('#openLoginModal').click();
        }

    </script>
@endpush