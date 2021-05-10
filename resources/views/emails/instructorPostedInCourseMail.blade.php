@component('mail::message')
# Hello, {{ $content['receiver_name'] }}

Your instructor has just posted an assignment critique, or answered a question on your course.

Regards,
{{ config('app.name') }}
@endcomponent
