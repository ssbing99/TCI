
<?php
//$fields = json_decode(config('registration_fields'));
//$inputs = ['text','number','date','gender'];
//dd($fields);
?>
@if(!auth()->check())

    <div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="loginTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form class="box" id="loginForm" action="{{route('frontend.auth.login.post')}}"
                      method="POST" enctype="multipart/form-data">
                    <a class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                    <h1>Login</h1>
                    <p>Fill Your Details</p>
                    <span class="error-response text-danger"></span>
                    <span class="success-response text-success">{{(session()->get('flash_success'))}}</span>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                        <input type="email" name="email" placeholder="Username">
                    </div>
                    <span id="login-email-error" class="text-danger"></span>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <input type="password" name="password" placeholder="Password">
                    </div>
                    <span id="login-password-error" class="text-danger"></span>
                    <a class="forgot" href="#">Forgot password?</a>
                    <input type="hidden" name="enrollId" id="enrollId" value="">
                    <input type="hidden" name="isGift" id="isGift" value="">
                    <input type="hidden" name="workshopId" id="workshopId" value="">
                    <input type="hidden" name="workshopType" id="workshopType" value="">
                    <input type="submit" name="" value="Login">
                    <div id="loginSocial" class="optbox clearfix">
                        <div class="divider">OR</div>
                        <div class="col-md-12" id="loginSocialLinks">
    {{--                        <ul class="social-network social-circle">--}}
    {{--                            <li><a href="#" class="icoFacebook" title="Facebook"><i class="fa fa-facebook-f"></i></a></li>--}}
    {{--                            <li><a href="#" class="icoInstagram" title="Instagram"><i class="fa fa-instagram"></i></a></li>--}}
    {{--                        </ul>--}}
                        </div>
                    </div>
                    <div class="label clearfix">Don't have an Account? <a href="{{route('frontend.auth.register')}}">Sign Up</a></div>
                </form>
                <form id="redirectForm" action="{{ route('cart.singleCheckout') }}" method="POST" class="mb-2">
                    <input type="hidden" name="_token" value=""/>
                    <input type="hidden" name="course_id" value=""/>
                    <input type="hidden" name="gift_course" value=""/>
                </form>
                <form id="redirectWorkshpoForm" action="{{ route('workshops.enroll.post') }}" method="POST" class="mb-2">
                    <input type="hidden" name="_token" value=""/>
                    <input type="hidden" name="id" value=""/>
                    <input type="hidden" name="type" value=""/>
                </form>
            </div>
        </div>
    </div>
@endif

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    @if (session('openModel'))
        <script>
            $('#login').modal('show');
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

            $('#loginSocial').hide();
            $('#signupSocial').hide();

            $(document).ready(function () {

                $('#login').on('hidden.bs.modal', function () {
                    //if close then clear value
                    $('#workshopId').val('');
                    $('#workshopType').val('');

                    $('#enrollId').val('');
                    $('#isGift').val('false');
                })


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
                            if(response.socialLinks){
                                $('#loginSocial').show();
                                // $('#signupSocial').show();
                            }else{
                                $('#loginSocial').hide();
                                // $('#signupSocial').hide();
                            }
                            $('#loginSocialLinks').html(response.socialLinks)
                            // $('#signupSocialLinks').html(response.socialLinks)
                            $('#login').modal('show');
                        },
                    });
                }

                $(document).on('click', '#openLoginModal', function(e) {showLogin('#tab-1','#tab-2')});
                $(document).on('click', '#openRegisterModal', function(e) {showLogin('#tab-2','#tab-1')});

                @if (session('openModel'))
                    showLogin('#tab-1','#tab-2')
                @endif

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
                                    if($('#enrollId').val().length > 0) {

                                        var form = document.getElementById('redirectForm');
                                        form.elements[0].value = response._newToken;
                                        form.elements[1].value = $('#enrollId').val();
                                        form.elements[2].value = $('#isGift').val();
                                        form.submit();
                                    }else if($('#workshopId').val().length > 0) {

                                        var form = document.getElementById('redirectWorkshpoForm');
                                        form.elements[0].value = response._newToken;
                                        form.elements[1].value = $('#workshopId').val();
                                        form.elements[2].value = $('#workshopType').val();
                                        form.submit();
                                    }else {
                                        location.reload();
                                    }
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
                                var displayErrMsg = response.message;
                                if (displayErrMsg === 'CSRF token mismatch.') {
                                    displayErrMsg = 'Something went wrong. Please try again later.';
                                }
                                $('.error-response').empty().html(displayErrMsg);
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
