@component('mail::message')
# Hello, {{ $content['receiver_name'] }}

You are receiving this email is because {{$user->full_name}} has bought you a course: <br>
<br/>
## Course Details <br>
You can purchase course with: {{ $gift->title }} <br>
Code: {{$content['code']}} (Use this code to claim your course for free) <br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
