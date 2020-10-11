
<?php
//$fields = json_decode(config('registration_fields'));
//$inputs = ['text','number','date','gender'];
//dd($fields);
?>
@if(!auth()->check())

    <div class="modal fade bd-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="card">
                    <div class="login-box">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
                        <div class="login-snip">
                            <input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">@lang('labels.frontend.modal.login')</label>
                            <input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab">@lang('labels.frontend.modal.signup')</label>
                            <div class="login-space">
                                <span class="error-response text-danger"></span>
                                <span class="success-response text-success">{{(session()->get('flash_success'))}}</span>
                                <div class="login" id="login">
                                    <div class="optbox clearfix">
                                        <h6>@lang('labels.frontend.modal.login_with')</h6>
                                        <div id="loginSocialLinks"></div>
{{--                                        <div class="imgarea clearfix">--}}
{{--                                            <a href="#"><img src="{{asset("assets_new/images/fb-icon.jpg")}}" alt="Image goes here" /></a>--}}
{{--                                            <a href="#"><img src="{{asset("assets_new/images/google-icon.jpg")}}" alt="Image goes here" /></a>--}}
{{--                                        </div>--}}
                                    </div>
                                    <form class="contact_form" id="loginForm" action="{{route('frontend.auth.login.post')}}"
                                          method="POST" enctype="multipart/form-data">
{{--                                        <a href="#" class="go-register float-left text-info pl-0">--}}
{{--                                            @lang('labels.frontend.modal.new_user_note')--}}
{{--                                        </a>--}}
                                        <div class="group">
                                            {{ html()->email('email')
                                                ->class('input')
                                                ->placeholder(__('validation.attributes.frontend.email'))
                                                ->attribute('maxlength', 191)
                                                }}
                                            <span id="login-email-error" class="text-danger"></span>

                                        </div>

                                        <div class="group">
                                            {{ html()->password('password')
                                                ->class('input')
                                                ->placeholder(__('validation.attributes.frontend.password'))
                                                }}
                                            <span id="login-password-error" class="text-danger"></span>

                                        </div>

{{--                                    <div class="group"><input id="user" type="text" class="input" placeholder="@lang('labels.frontend.modal.enter_y_username')"> </div>--}}
{{--                                    <div class="group"><input id="pass" type="password" class="input" data-type="password" placeholder="@lang('labels.frontend.modal.enter_y_password')"> </div>--}}
                                    <div class="group"> <input id="check" name="remember" type="checkbox" class="check" checked value="1"> <label for="check"><span class="icon"></span> @lang('labels.frontend.modal.keep_signin')</label> </div>

                                    @if(config('access.captcha.registration'))
                                        <div class="group text-center">
                                            {{ no_captcha()->display() }}
                                            {{ html()->hidden('captcha_status', 'true') }}
                                            <span id="login-captcha-error" class="text-danger"></span>

                                        </div><!--col-->
                                    @endif

                                    <div class="group"> <input type="submit" class="button" value="@lang('labels.frontend.modal.login')"> </div>
                                    <div class="hr"></div>
                                    <div class="foot">
                                        <a href="{{ route('frontend.auth.password.reset') }}">@lang('labels.frontend.passwords.forgot_password')</a>
                                    </div>
                                    </form>
                                </div>
                                <div class="sign-up-form" id="register">
                                    <div class="optbox clearfix">
                                        <h6>@lang('labels.frontend.modal.signup_with')</h6>
                                        <div id="signupSocialLinks"></div>
{{--                                        <div class="imgarea clearfix">--}}
{{--                                            <a href="#"><img src="{{asset("assets_new/images/fb-icon.jpg")}}" alt="Image goes here" /></a>--}}
{{--                                            <a href="#"><img src="{{asset("assets_new/images/google-icon.jpg")}}" alt="Image goes here" /></a>--}}
{{--                                        </div>--}}
                                    </div>

                                    <form id="registerForm" class="contact_form"
                                          action="#"
                                          method="post">
                                        {!! csrf_field() !!}


                                        <div class="group">

                                            {{ html()->text('first_name')
                                                ->class('input')
                                                ->placeholder(__('validation.attributes.frontend.first_name'))
                                                ->attribute('maxlength', 191) }}
                                            <span id="first-name-error" class="text-danger"></span>
                                        </div>

                                        <div class="group">
                                            {{ html()->text('last_name')
                                              ->class('input')
                                              ->placeholder(__('validation.attributes.frontend.last_name'))
                                              ->attribute('maxlength', 191) }}
                                            <span id="last-name-error" class="text-danger"></span>

                                        </div>

                                        <div class="group">
                                            {{ html()->email('email')
                                               ->class('input')
                                               ->placeholder(__('validation.attributes.frontend.email'))
                                               ->attribute('maxlength', 191)
                                               }}
                                            <span id="email-error" class="text-danger"></span>

                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 group">
                                                {{ html()->password('password')
                                                    ->class('input')
                                                    ->placeholder(__('validation.attributes.frontend.password'))
                                                     }}
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 group">
                                                {{ html()->password('password_confirmation')
                                                    ->class('input')
                                                    ->placeholder(__('validation.attributes.frontend.password_confirmation'))
                                                     }}
                                            </div>
                                        </div>

                                        <div class="group">
                                            <span id="password-error" class="text-danger"></span>
                                        </div>


{{--                                    <div class="group"><input id="user" type="text" class="input" placeholder="Username"></div>--}}
{{--                                    <div class="group"><input id="email" type="email" class="input" placeholder="Email ID"></div>--}}
{{--                                    <div class="row clearfix">--}}
{{--                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 group"><input id="password" type="password" class="input" data-type="password" placeholder="Password"> </div>--}}
{{--                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 group"><input id="confirmPassword" type="password" class="input" data-type="password" placeholder="Retype password"> </div>--}}
{{--                                    </div>--}}
                                    <div class="group"> <input type="submit" class="button" value="@lang('labels.frontend.modal.signup')"> </div>
                                    <div class="hr"></div>
                                    <div class="foot">
                                        <a href="{{ route('frontend.auth.teacher.register') }}" >
                                            @lang('labels.teacher.teacher_register')
                                        </a>
                                    </div>
                                    <div class="foot"> <label for="tab-1">Already Member?</label> </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@push('after-scripts')
    @if (session('openModel'))
        <script>
            $('#myModal').modal('show');
        </script>
    @endif


    @if(config('access.captcha.registration'))
        {{ no_captcha()->script() }}

    @endif

    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).ready(function () {
                $(document).on('click', '#openRegisterModal', function () {
                    $('#tab-1').removeAttr('checked');
                    $('#tab-2').prop('checked','checked');
                });
                $(document).on('click', '.go-login', function () {
                    $('#register').removeClass('active').addClass('fade')
                    $('#login').addClass('active').removeClass('fade')

                });
                $(document).on('click', '.go-register', function () {
                    $('#login').removeClass('active').addClass('fade')
                    $('#register').addClass('active').removeClass('fade')
                });

                function showLogin (tab1, tab2) {
                    $(tab2).removeAttr('checked');
                    $(tab1).prop('checked','checked');
                    $.ajax({
                        type: "GET",
                        url: "{{route('frontend.auth.login')}}",
                        success: function (response) {
                            $('#loginSocialLinks').html(response.socialLinks)
                            $('#signupSocialLinks').html(response.socialLinks)
                            $('#myModal').modal('show');
                        },
                    });
                }

                $(document).on('click', '#openLoginModal', function(e) {showLogin('#tab-1','#tab-2')});
                $(document).on('click', '#openRegisterModal', function(e) {showLogin('#tab-2','#tab-1')});

                $('#loginForm').on('submit', function (e) {
                    e.preventDefault();

                    var $this = $(this);
                    $('.success-response').empty();
                    $('.error-response').empty();

                    $.ajax({
                        type: $this.attr('method'),
                        url: $this.attr('action'),
                        data: $this.serializeArray(),
                        dataType: $this.data('type'),
                        success: function (response) {
                            $('#login-email-error').empty();
                            $('#login-password-error').empty();
                            $('#login-captcha-error').empty();

                            if (response.errors) {
                                if (response.errors.email) {
                                    $('#login-email-error').html(response.errors.email[0]);
                                }
                                if (response.errors.password) {
                                    $('#login-password-error').html(response.errors.password[0]);
                                }

                                var captcha = "g-recaptcha-response";
                                if (response.errors[captcha]) {
                                    $('#login-captcha-error').html(response.errors[captcha][0]);
                                }
                            }
                            if (response.success) {
                                $('#loginForm')[0].reset();
                                if (response.redirect == 'back') {
                                    location.reload();
                                } else {
                                    window.location.href = "{{route('admin.dashboard')}}"
                                }
                            }
                        },
                        error: function (jqXHR) {
                            var response = $.parseJSON(jqXHR.responseText);
                            console.log(jqXHR)
                            if (response.message) {
                                // $('#login').find('span.error-response').html(response.message)
                                $('.error-response').empty().html(response.message);
                            }
                        }
                    });
                });

                $('#registerForm').on('submit', function (e) {
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
                            $('#first-name-error').empty()
                            $('#last-name-error').empty()
                            $('#email-error').empty()
                            $('#password-error').empty()
                            $('#captcha-error').empty()
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
                                $('#registerForm')[0].reset();
                                // $('#register').removeClass('active').addClass('fade')
                                $('.error-response').empty();
                                // $('#login').addClass('active').removeClass('fade')
                                $('#tab-1').prop('checked','checked');
                                $('.success-response').empty().html("@lang('labels.frontend.modal.registration_message')");
                            }
                        }
                    });
                });
            });

        });
    </script>
@endpush
