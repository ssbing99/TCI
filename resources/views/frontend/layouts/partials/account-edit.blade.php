{{--<form method="patch" action="{{route('admin.profile.update')}}" role="form" enctype="multipart/form-data">--}}
{{ html()->modelForm($user, 'PATCH', route('admin.profile.updateProfile'))->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() }}
<input type="hidden" name="avatar_type" value="storage"/>
    <ul class="form-list clearfix">
        <li>
            <div class="row clearfix">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <label>@lang('validation.attributes.frontend.email')<span>*</span></label>
                    <input type="email" name="email" id="email" class="form-control custom-input" placeholder="" value="{{$user->email}}" disabled />
                </div>
            </div>
        </li>
    </ul>
    <p class="head clearfix">Name</p>
    <ul class="form-list clearfix">
        <li>
            <div class="row clearfix">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <label>@lang('validation.attributes.frontend.first_name')<span>*</span></label>
                    <input type="text" name="first_name" id="first_name" class="form-control custom-input" placeholder="Ingrid" required value="{{$user->first_name}}"/>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <label>@lang('validation.attributes.frontend.last_name')<span>*</span></label>
                    <input type="text" name="last_name" id="last_name" class="form-control custom-input" placeholder="Toner" required value="{{$user->last_name}}"/>
                </div>
            </div>
        </li>
        <li>
            <div class="row clearfix">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <label>Photo</label>
                    <input type="file" name="avatar_location" id="avatar_location" class="form-control custom-input" placeholder="Photo" />
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <label>&nbsp;</label>
                    <div class="photo clearfix">
                        <img src="{{auth()->user()->picture}}" alt="" />
                    </div>
                </div>
            </div>
        </li>
        @if(auth()->user()->hasRole('teacher'))
            @php
                $teacherProfile = auth()->user()->teacherProfile?:'';
                $payment_details = auth()->user()->teacherProfile?json_decode(auth()->user()->teacherProfile->payment_details):optional();
            @endphp
            <li>
                <div class="row clearfix">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <div class="col-10 form-group">
                            <label>Gender<span>*</span></label>
                            <div class="col-12 form-group">
                                <input type="radio" name="gender" id="gender" {{$user->gender == 'male'? 'checked' : ''}} required value="male"/> Male
                                <input type="radio" name="gender" id="gender" {{$user->gender == 'female'? 'checked' : ''}} required value="female"/> Female
                                <input type="radio" name="gender" id="gender" {{$user->gender == 'other'? 'checked' : ''}} required value="other"/> Other
                            </div>
                        </div>
                    </div>
                </div>
            </li>

        <!-- LINK -->

            <li>
                <div class="row clearfix">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <label>@lang('labels.teacher.facebook_link')</label>
                        <input type="text" name="facebook_link" id="facebook_link" class="form-control custom-input" placeholder="" value="{{$teacherProfile->facebook_link}}"/>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <label>@lang('labels.teacher.twitter_link')</label>
                        <input type="text" name="twitter_link" id="twitter_link" class="form-control custom-input" placeholder="" value="{{$teacherProfile->twitter_link}}"/>
                    </div>
                </div>
            </li>
            <li>
                <div class="row clearfix">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <label>@lang('labels.teacher.linkedin_link')</label>
                        <input type="text" name="linkedin_link" id="linkedin_link" class="form-control custom-input" placeholder="" value="{{$teacherProfile->linkedin_link}}"/>
                    </div>
                </div>
            </li>

            <li>
                <div class="row clearfix">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <label>@lang('labels.teacher.payment_details')</label>
                        <select class="form-control custom-input" name="payment_method" id="payment_method" required>
                            <option value="bank" {{ $teacherProfile->payment_method == 'bank'?'selected':'' }}>{{ trans('labels.teacher.bank') }}</option>
                            <option value="paypal" {{ $teacherProfile->payment_method == 'paypal'?'selected':'' }}>{{ trans('labels.teacher.paypal') }}</option>
                        </select>
                    </div>
                </div>
            </li>

            <!-- Bank Details -->
            <li id="bank_details1" style="display:{{ $logged_in_user->teacherProfile->payment_method == 'bank'?'':'none' }}">
                <div class="row clearfix">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <label>@lang('labels.teacher.bank_details.name')<span>*</span></label>
                        <input type="text" name="bank_name" id="bank_name" class="form-control custom-input" placeholder="" value="{{$payment_details?$payment_details->bank_name:''}}"/>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <label>@lang('labels.teacher.bank_details.bank_code')<span>*</span></label>
                        <input type="text" name="ifsc_code" id="ifsc_code" class="form-control custom-input" placeholder="" value="{{$payment_details?$payment_details->ifsc_code:''}}"/>
                    </div>
                </div>
            </li>
            <li id="bank_details2" style="display:{{ $logged_in_user->teacherProfile->payment_method == 'bank'?'':'none' }}">
                <div class="row clearfix">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <label>@lang('labels.teacher.bank_details.account')<span>*</span></label>
                        <input type="text" name="account_number" id="account_number" class="form-control custom-input" placeholder="" value="{{$payment_details?$payment_details->account_number:''}}"/>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <label>@lang('labels.teacher.bank_details.holder_name')<span>*</span></label>
                        <input type="text" name="account_name" id="account_name" class="form-control custom-input" placeholder="" value="{{$payment_details?$payment_details->account_name:''}}"/>
                    </div>
                </div>
            </li>

            <!-- PAYPAL -->

            <li id="paypal_details" style="display:{{ $logged_in_user->teacherProfile->payment_method == 'paypal'?'':'none' }}">
                <div class="row clearfix">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <label>@lang('labels.teacher.paypal_email')</label>
                        <input type="text" name="paypal_email" id="paypal_email" class="form-control custom-input" placeholder="" value="{{$payment_details?$payment_details->paypal_email:''}}"/>
                    </div>
                </div>
            </li>
        @endif
    </ul>



    <p class="head clearfix">Address</p>
    <ul class="form-list clearfix">
        <li>
            <div class="row clearfix">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <label>@lang('validation.attributes.frontend.street_addr')<span>*</span></label>
                    <input type="text" name="address" id="address" class="form-control custom-input" placeholder="2/64" required value="{{$user->address}}"/>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <label>@lang('validation.attributes.frontend.locality')<span>*</span></label>
                    <input type="text" name="city" id="city" class="form-control custom-input" placeholder="Newyork" required value="{{$user->city}}"/>
                </div>
            </div>
        </li>
        <li>
            <div class="row clearfix">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <label>@lang('validation.attributes.frontend.region')<span>*</span></label>
                    <input type="text" name="state" id="state" class="form-control custom-input" placeholder="Brooklyn Street" required value="{{$user->state}}"/>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <label>@lang('validation.attributes.frontend.postal_code')<span>*</span></label>
                    <input type="text" name="postal" id="postal" class="form-control custom-input" placeholder="121342" value="{{$user->postal}}"/>
                </div>
            </div>
        </li>
        <li>
            <div class="row clearfix">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <label>@lang('validation.attributes.frontend.country')<span>*</span></label>
                    <input type="text" name="country" id="country" class="form-control custom-input" placeholder="USA" required value="{{$user->country}}"/>
                </div>
            </div>
        </li>
    </ul>
    <p class="head clearfix">Contact Details</p>
    <ul class="form-list clearfix">
        <li>
            <div class="row clearfix">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <label>Telephone(Landline)<span>*</span></label>
                    <input type="text" name="accountPhone" id="accountPhone" class="form-control custom-input" placeholder="1-87654321" required value="{{$user->phone}}"/>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <label>Telephone(Mobile)<span>*</span></label>
                    <input type="text" name="phone" id="phone" class="form-control custom-input" placeholder="9876543210" required value="{{$user->phone}}"/>
                </div>
            </div>
        </li>
    </ul>
    <input type="submit" name="submit" id="submit" class="btn btn-primary btn-padding br-24" value="Submit" />
{{ html()->closeModelForm() }}
@push('after-scripts')
    <script>
        $(document).on('change', '#payment_method', function(){
            if($(this).val() === 'bank'){
                $('#paypal_details').hide();
                $('#bank_details1').show();
                $('#bank_details2').show();
            }else{
                $('#paypal_details').show();
                $('#bank_details1').hide();
                $('#bank_details2').hide();
            }
        });
    </script>
@endpush
