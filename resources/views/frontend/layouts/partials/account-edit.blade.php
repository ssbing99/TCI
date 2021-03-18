{{--<form method="patch" action="{{route('admin.profile.update')}}" role="form" enctype="multipart/form-data">--}}
{{ html()->modelForm($user, 'PATCH', route('admin.profile.updateProfile'))->class('form-horizontal')->attribute('enctype', 'multipart/form-data')->open() }}
<input type="hidden" name="avatar_type" value="storage"/>
    <ul class="form-list clearfix">
        <li>
            <div class="row clearfix">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <label>@lang('validation.attributes.frontend.email')<span>*</span></label>
                    <input type="email" name="email" id="email" class="form-control custom-input" placeholder="toner.ingrid@gmail.com" disabled />
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
                    <input type="text" name="postal" id="postal" class="form-control custom-input" placeholder="121342" required value="{{$user->postal}}"/>
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
{{--</form>--}}