<p>@lang('strings.emails.contact.email_body_title')</p>

<p><strong>@lang('validation.attributes.frontend.name'):</strong> {{ $request->firstName }} {{ $request->lastName }}</p>
<p><strong>@lang('validation.attributes.frontend.email'):</strong> {{ $request->email }}</p>
<p><strong>@lang('validation.attributes.frontend.phone'):</strong>  {{ ($request->phone == "") ? "N/A" : $request->phone }}</p>
<p><strong>@lang('validation.attributes.frontend.message'):</strong> {{ $request->message }}</p>
