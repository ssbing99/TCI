{{ html()->form('PATCH', route('admin.account.post',$user->email))->class('form-horizontal')->open() }}
    <ul class="form-list clearfix">
        <li>
            <div class="row clearfix">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <label>Password</label>
                    <input type="password" name="password" id="password" class="form-control custom-input" placeholder="Password" required/>
                    <small class="form-text text-muted">Must be atleast 6 Letters and Numbers</small>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <label>Re-type Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control custom-input" placeholder="Re-type Password" required/>
                </div>
            </div>
        </li>
    </ul>
    <input type="submit" name="submit" id="submit" class="btn btn-primary btn-padding br-24" value="Submit" />
{{ html()->form()->close() }}