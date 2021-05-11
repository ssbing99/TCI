@component('mail::message')
# Hello, {{ $content['receiver_name'] }}

{{ $content['student_name'] }} has just posted to your course {{ $content['title'] }}. Please answer questions within 24 hours and post assignment critiques within 48 hours.
Thanks!

Regards,
{{ config('app.name') }}
@endcomponent
