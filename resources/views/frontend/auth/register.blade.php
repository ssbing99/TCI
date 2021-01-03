@extends('frontend.layouts.app'.config('theme_layout'))

@section('title', app_name() . ' | ' . __('labels.frontend.auth.register_box_title'))

@section('content')
    <section class="signup-bg clearfix">
        <div class="container">
            <div class="row clearfix">
                <div class="col-12">
                    <ul class="signup clearfix">
                        <li>
                            <div class="signup-content clearfix">
                                <div class="c clearfix">
                                    <img src="{{asset('assets_new/images/c.png')}}" alt="Images goes here" />
                                    <h4>Get Started</h4>
                                    <p>Getting started is fast and easy, simply :</p>
                                    <ul class="list clearfix">
                                        <li>Sign up for a free TCI account.</li>
                                        <li>Choose from our list of inspiring courses.</li>
                                        <li>Make your secure payment using Pay Pal or major credit card.</li>
                                        <li>Receive quick confirmation of your payment, log into your Student Area and you’re ready to begin!</li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="signup-form clearfix">
                                <form id="registerPageForm" method="post" action="" class="form clearfix" role="form">
                                    {!! csrf_field() !!}
                                    <legend>Create An Account</legend>
                                    <span class="error-response text-danger"></span>
                                    <span class="success-response text-success">{{(session()->get('flash_success'))}}</span>
                                    <div class="row clearfix">
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                            <div class="input-group custom-group form-group">
                                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                                {{ html()->text('first_name')
                                                ->class('form-control')
                                                ->placeholder(__('validation.attributes.frontend.first_name'))
                                                ->attribute('maxlength', 191) }}
                                            </div>
                                            <span id="first-name-error" class="text-danger"></span>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                            <div class="input-group custom-group form-group">
                                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                                {{ html()->text('last_name')
                                                ->class('form-control')
                                                ->placeholder(__('validation.attributes.frontend.last_name'))
                                                ->attribute('maxlength', 191) }}
                                            </div>
                                            <span id="last-name-error" class="text-danger"></span>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <div class="input-group custom-group form-group">
                                                <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                                                {{ html()->email('email')
                                                ->class('form-control')
                                                ->placeholder(__('validation.attributes.frontend.email'))
                                                ->attribute('maxlength', 191)
                                                ->required() }}
                                            </div>
                                            <span id="email-error" class="text-danger"></span>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                            <div class="input-group custom-group form-group">
                                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                                {{ html()->password('password')
                                                ->class('form-control')
                                                ->placeholder(__('validation.attributes.frontend.password'))
                                                ->required() }}
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                            <div class="input-group custom-group form-group">
                                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                                                {{ html()->password('password_confirmation')
                                                ->class('form-control')
                                                ->placeholder(__('validation.attributes.frontend.password_confirmation'))
                                                ->required() }}
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <span id="password-error" class="text-danger"></span>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                            <div class="input-group custom-group form-group">
                                                <span class="input-group-text"><i class="fa fa-building"></i></span>
                                                {{ html()->text('address')
                                                ->class('form-control')
                                                ->placeholder(__('validation.attributes.frontend.street_addr'))
                                                 }}
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                            <div class="input-group custom-group form-group">
                                                <span class="input-group-text"><i class="fa fa-building"></i></span>
                                                {{ html()->text('city')
                                                ->class('form-control')
                                                ->placeholder(__('validation.attributes.frontend.locality'))
                                                ->attribute('maxlength', 191) }}
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                            <div class="input-group custom-group form-group">
                                                <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                                                {{ html()->text('state')
                                                ->class('form-control')
                                                ->placeholder(__('validation.attributes.frontend.region'))
                                                ->attribute('maxlength', 191) }}
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                            <div class="input-group custom-group form-group">
                                                <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                                                {{ html()->text('postal')
                                                ->class('form-control')
                                                ->placeholder(__('validation.attributes.frontend.postal_code'))
                                                ->attribute('maxlength', 191) }}
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                            <div class="input-group custom-group form-group">
                                                <span class="input-group-text"><i class="fa fa-globe"></i></span>
                                                {{ html()->text('country')
                                                ->class('form-control')
                                                ->placeholder(__('validation.attributes.frontend.country'))
                                                ->attribute('maxlength', 191) }}
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <center><input type="submit" id="submit" name="submit" class="btn btn-primary btn-lg custom-button" value="SIGN UP" /></center>
                                        </div>
                                    </div>
                                    <div id="signupSocial" class="separator clearfix">Sign Up With</div>
                                    <div id="signupSocialLinks" class="social-btn clearfix">
                                        {!! $socialiteLinks !!}
{{--                                        <a href="#"><img src="{{asset('assets_new/images/facebook.jpg')}}" alt="" /></a>--}}
{{--                                        <a href="#"><img src="{{asset('assets_new/images/instagram.jpg')}}" alt="" /></a>--}}
                                    </div>
                                    <div class="option clearfix">Already have an Account? <a href="#" data-toggle="modal" data-target="#login">Login</a></div>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('after-scripts')
    @if(config('access.captcha.registration'))
        {!! Captcha::script() !!}
    @endif

    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).ready(function () {

                {{--$.ajax({--}}
                {{--    type: "GET",--}}
                {{--    url: "{{route('frontend.auth.login')}}",--}}
                {{--    success: function (response) {--}}
                {{--        if(response.socialLinks){--}}
                {{--            $('#signupSocial').show();--}}
                {{--        }else{--}}
                {{--            $('#signupSocial').hide();--}}
                {{--        }--}}
                {{--        $('#signupSocialLinks').html(response.socialLinks)--}}
                {{--        // $('#myModal').modal('show');--}}
                {{--    },--}}
                {{--});--}}

                $('#registerPageForm').on('submit', function (e) {
                    // $(document).on('submit','#registerForm', function (e) {
                    e.preventDefault();
                    console.log('he')
                    var $this = $(this);

                    $.ajax({
                        type: $this.attr('method'),
                        url: "{{  route('frontend.auth.register.post')}}",
                        data: $this.serializeArray(),
                        dataType: $this.data('type'),
                        success: function (data) {
                            $('#first-name-error').empty();
                            $('#last-name-error').empty();
                            $('#email-error').empty();
                            $('#password-error').empty();
                            $('#captcha-error').empty();
                            if (data.errors) {
                                if (data.errors.first_name) {
                                    $('#first-name-error').html(data.errors.first_name[0]);
                                }
                                if (data.errors.last_name) {
                                    $('#last-name-error').html(data.errors.last_name[0]);
                                }
                                if (data.errors.email) {
                                    $('#email-error').html(data.errors.email[0]);
                                }
                                if (data.errors.password) {
                                    $('#password-error').html(data.errors.password[0]);
                                }

                                var captcha = "g-recaptcha-response";
                                if (data.errors[captcha]) {
                                    $('#captcha-error').html(data.errors[captcha][0]);
                                }
                            }
                            if (data.success) {
                                $('#registerPageForm')[0].reset();
                                // $('#register').removeClass('active').addClass('fade')
                                $('.error-response').empty();
                                // $('#login').addClass('active').removeClass('fade')
                                $('.success-response').empty().html("@lang('labels.frontend.modal.registration_message')");
                            }
                        }
                    });
                });
            });

        });
    </script>
@endpush
