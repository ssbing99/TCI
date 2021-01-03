@extends('frontend.layouts.app'.config('theme_layout'))
@push('after-styles')
    <style>
        .couse-pagination li.active {
            color: #333333!important;
            font-weight: 700;
        }
        .page-link {
            position: relative;
            display: block;
            padding: .5rem .75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #c7c7c7;
            background-color: white;
            border: none;
        }
        .page-item.active .page-link {
            z-index: 1;
            color: #333333;
            background-color:white;
            border:none;

        }
        ul.pagination{
            display: inline;
            text-align: center;
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
					<h1>Our Instructors</h1>
				</div>
			</div>
		</div>
	</header>
	<!-- End of breadcrumb section
		============================================= -->



	<!-- Start of teacher section
		============================================= -->
	<section>
		<div class="container">
			<div class="row clearfix">
				@if(count($teachers) > 0)
					@foreach($teachers as $item)
						<?php
						$teacherProfile = $item->teacherProfile?:'';
						?>
						<div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">
							<div class="instructors-grid clearfix">
								<div class="instructors-img clearfix">
									<img src="{{$item->picture}}" alt="" />
								</div>
								<a href="{{route('teachers.show',['id'=>$item->id])}}"><img src="{{$item->picture}}" class="instructors-profile" alt="" /></a>
								<div class="instructors-content clearfix">
									<div class="instructors-title clearfix"><a href="{{route('teachers.show',['id'=>$item->id])}}">{{$item->full_name}}<span>{{$teacherProfile->title}}</span></a></div>
									<p>{{substr(preg_replace('#\<(.*?)\>#', '', $teacherProfile->description), 0,200).'...'}}</p>
									<ul class="instructors-social clearfix">
										@if(isset($teacherProfile->facebook_link))
											<li><a href="{{$teacherProfile->facebook_link}}"><i class="fa fa-facebook"></i></a></li>
										@endif
										@if(isset($teacherProfile->insta_link))
											<li><a href="{{$teacherProfile->insta_link}}"><i class="fa fa-instagram"></i></a></li>
										@endif
									</ul>
								</div>
							</div>
						</div>
					@endforeach
{{--				@else--}}
{{--					<h4>@lang('lables.general.no_data_available')</h4>--}}
				@endif
{{--				<div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">--}}
{{--					<div class="instructors-grid clearfix">--}}
{{--						<div class="instructors-img clearfix">--}}
{{--							<img src="{{asset("assets_new/images/gmb.jpg")}}" alt="Images goes here" />--}}
{{--						</div>--}}
{{--						<a href="instructor-details.html"><img src="{{asset("assets_new/images/gmb-akash.jpg")}}" class="instructors-profile" alt="Images goes here" /></a>--}}
{{--						<div class="instructors-content clearfix">--}}
{{--							<div class="instructors-title clearfix"><a href="instructor-details.html">GMB Akash<span>Street Photography with G.M.B. Akash</span></a></div>--}}
{{--							<p>Akash's passion for photography began in 1996. He attended the World Press Photo seminar in Dhaka for 3 years and graduated with a BA in Photojournalism from Pathshala, Dhaka. He has ...</p>--}}
{{--							<ul class="instructors-social clearfix">--}}
{{--								<li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
{{--								<li><a href="#"><i class="fa fa-instagram"></i></a></li>--}}
{{--							</ul>--}}
{{--						</div>--}}
{{--					</div>--}}
{{--				</div>--}}
{{--				<div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">--}}
{{--					<div class="instructors-grid clearfix">--}}
{{--						<div class="instructors-img clearfix">--}}
{{--							<img src="{{asset("assets_new/images/db.jpg")}}" alt="Images goes here" />--}}
{{--						</div>--}}
{{--						<a href="instructor-details.html"><img src="{{asset("assets_new/images/david-bathgate.jpg")}}" class="instructors-profile" alt="Images goes here" /></a>--}}
{{--						<div class="instructors-content clearfix">--}}
{{--							<div class="instructors-title clearfix"><a href="instructor-details.html">David Bathgate<span>People Photography - with Confidence</span></a></div>--}}
{{--							<p>David Bathgate is an award-winning documentary and travel photographer whose work appears in such publications as Time, Newsweek, The New York Times, Geo, Stern, The Guardian, The Times of London and the ...</p>--}}
{{--							<ul class="instructors-social clearfix">--}}
{{--								<li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
{{--								<li><a href="#"><i class="fa fa-instagram"></i></a></li>--}}
{{--							</ul>--}}
{{--						</div>--}}
{{--					</div>--}}
{{--				</div>--}}
{{--				<div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">--}}
{{--					<div class="instructors-grid clearfix">--}}
{{--						<div class="instructors-img clearfix">--}}
{{--							<img src="{{asset("assets_new/images/ac.jpg")}}" alt="Images goes here" />--}}
{{--						</div>--}}
{{--						<a href="instructor-details.html"><img src="{{asset("assets_new/images/arlene-collins.jpg")}}" class="instructors-profile" alt="Images goes here" /></a>--}}
{{--						<div class="instructors-content clearfix">--}}
{{--							<div class="instructors-title clearfix"><a href="instructor-details.html">Arlene Collins<span>Travel Photography</span></a></div>--}}
{{--							<p>Arlene Collins is a New York City based photographer specializing in documenting remote cultures and changing civilizations around the world.  Since 2000 she has produced and lead international photography workshops to ...</p>--}}
{{--							<ul class="instructors-social clearfix">--}}
{{--								<li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
{{--								<li><a href="#"><i class="fa fa-instagram"></i></a></li>--}}
{{--							</ul>--}}
{{--						</div>--}}
{{--					</div>--}}
{{--				</div>--}}
{{--				<div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">--}}
{{--					<div class="instructors-grid clearfix">--}}
{{--						<div class="instructors-img clearfix">--}}
{{--							<img src="{{asset("assets_new/images/gg.jpg")}}" alt="Images goes here" />--}}
{{--						</div>--}}
{{--						<a href="instructor-details.html"><img src="{{asset("assets_new/images/gina-genis.jpg")}}" class="instructors-profile" alt="Images goes here" /></a>--}}
{{--						<div class="instructors-content clearfix">--}}
{{--							<div class="instructors-title clearfix"><a href="instructor-details.html">Gina Genis<span>Capturing Breathetaking Landscapes</span></a></div>--}}
{{--							<p>Gina Genis is a graduate of Parsons School of Design in N.Y., and Fashion Institute of Design and Merchandising, L.A. She leads the Gina Genis Photography Workshops where she shares the ...</p>--}}
{{--							<ul class="instructors-social clearfix">--}}
{{--								<li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
{{--								<li><a href="#"><i class="fa fa-instagram"></i></a></li>--}}
{{--							</ul>--}}
{{--						</div>--}}
{{--					</div>--}}
{{--				</div>--}}
{{--				<div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">--}}
{{--					<div class="instructors-grid clearfix">--}}
{{--						<div class="instructors-img clearfix">--}}
{{--							<img src="{{asset("assets_new/images/gmb.jpg")}}" alt="Images goes here" />--}}
{{--						</div>--}}
{{--						<a href="instructor-details.html"><img src="{{asset("assets_new/images/gmb-akash.jpg")}}" class="instructors-profile" alt="Images goes here" /></a>--}}
{{--						<div class="instructors-content clearfix">--}}
{{--							<div class="instructors-title clearfix"><a href="instructor-details.html">GMB Akash<span>Street Photography with G.M.B. Akash</span></a></div>--}}
{{--							<p>Akash's passion for photography began in 1996. He attended the World Press Photo seminar in Dhaka for 3 years and graduated with a BA in Photojournalism from Pathshala, Dhaka. He has ...</p>--}}
{{--							<ul class="instructors-social clearfix">--}}
{{--								<li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
{{--								<li><a href="#"><i class="fa fa-instagram"></i></a></li>--}}
{{--							</ul>--}}
{{--						</div>--}}
{{--					</div>--}}
{{--				</div>--}}
{{--				<div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">--}}
{{--					<div class="instructors-grid clearfix">--}}
{{--						<div class="instructors-img clearfix">--}}
{{--							<img src="{{asset("assets_new/images/db.jpg")}}" alt="Images goes here" />--}}
{{--						</div>--}}
{{--						<a href="instructor-details.html"><img src="{{asset("assets_new/images/david-bathgate.jpg")}}" class="instructors-profile" alt="Images goes here" /></a>--}}
{{--						<div class="instructors-content clearfix">--}}
{{--							<div class="instructors-title clearfix"><a href="instructor-details.html">David Bathgate<span>People Photography - with Confidence</span></a></div>--}}
{{--							<p>David Bathgate is an award-winning documentary and travel photographer whose work appears in such publications as Time, Newsweek, The New York Times, Geo, Stern, The Guardian, The Times of London and the ...</p>--}}
{{--							<ul class="instructors-social clearfix">--}}
{{--								<li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
{{--								<li><a href="#"><i class="fa fa-instagram"></i></a></li>--}}
{{--							</ul>--}}
{{--						</div>--}}
{{--					</div>--}}
{{--				</div>--}}
{{--				<div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">--}}
{{--					<div class="instructors-grid clearfix">--}}
{{--						<div class="instructors-img clearfix">--}}
{{--							<img src="{{asset("assets_new/images/ac.jpg")}}" alt="Images goes here" />--}}
{{--						</div>--}}
{{--						<a href="instructor-details.html"><img src="{{asset("assets_new/images/arlene-collins.jpg")}}" class="instructors-profile" alt="Images goes here" /></a>--}}
{{--						<div class="instructors-content clearfix">--}}
{{--							<div class="instructors-title clearfix"><a href="instructor-details.html">Arlene Collins<span>Travel Photography</span></a></div>--}}
{{--							<p>Arlene Collins is a New York City based photographer specializing in documenting remote cultures and changing civilizations around the world.  Since 2000 she has produced and lead international photography workshops to ...</p>--}}
{{--							<ul class="instructors-social clearfix">--}}
{{--								<li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
{{--								<li><a href="#"><i class="fa fa-instagram"></i></a></li>--}}
{{--							</ul>--}}
{{--						</div>--}}
{{--					</div>--}}
{{--				</div>--}}
{{--				<div class="col-12 col-sm-4 col-md-4 col-lg-3 col-xl-3">--}}
{{--					<div class="instructors-grid clearfix">--}}
{{--						<div class="instructors-img clearfix">--}}
{{--							<img src="{{asset("assets_new/images/gg.jpg")}}" alt="Images goes here" />--}}
{{--						</div>--}}
{{--						<a href="instructor-details.html"><img src="{{asset("assets_new/images/gina-genis.jpg")}}" class="instructors-profile" alt="Images goes here" /></a>--}}
{{--						<div class="instructors-content clearfix">--}}
{{--							<div class="instructors-title clearfix"><a href="instructor-details.html">Gina Genis<span>Capturing Breathetaking Landscapes</span></a></div>--}}
{{--							<p>Gina Genis is a graduate of Parsons School of Design in N.Y., and Fashion Institute of Design and Merchandising, L.A. She leads the Gina Genis Photography Workshops where she shares the ...</p>--}}
{{--							<ul class="instructors-social clearfix">--}}
{{--								<li><a href="#"><i class="fa fa-facebook"></i></a></li>--}}
{{--								<li><a href="#"><i class="fa fa-instagram"></i></a></li>--}}
{{--							</ul>--}}
{{--						</div>--}}
{{--					</div>--}}
{{--				</div>--}}
			</div>
		</div>
	</section>
	<!-- End of teacher section
		============================================= -->



@endsection