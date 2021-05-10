@component('mail::message')
# Hello, {{ $content['receiver_name'] }}

A student has just enrolled on your {{$course->title}} course. Please go to your Instructor Area and send them a brief “Welcome to the Course” message placed in the Discussion field at the bottom of the first Lesson.  
Thanks!
Regards,
{{ config('app.name') }}
@endcomponent
