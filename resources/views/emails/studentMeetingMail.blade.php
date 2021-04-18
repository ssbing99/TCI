@component('mail::message')
Hello **{{$content['name']}}**

You have been invited to Zoom Meeting for **{{ $content['course'] }}**.

Zoom Meeting ID **{{ $content['meeting_id'] }}** <br>
Password **{{ $content['password'] }}** <br>
Date **{{ $content['start_at']->format('d-m-Y h:i A') }} ({{ config('zoom.timezone') }})** <br>


@component('mail::button', ['url' => 'https://us04web.zoom.us/j/'.$content['meeting_id']])
Join URL
@endcomponent

[https://us04web.zoom.us/j/{{ $content['meeting_id'] }}](https://us04web.zoom.us/j/{{ $content['meeting_id'] }}).


Thanks,<br>
{{ config('app.name') }}
@endcomponent
