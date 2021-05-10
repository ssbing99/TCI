@component('mail::message')
# Hello, {{ auth()->user()->name }}

Welcome to the Compelling Image!  
Thank you for signing up for the {{ $content['title'] }} course. You’ll be receiving a personal greeting from your instructor soon. In the meantime, you can sign in and access your Student Area, where your first lesson and assignment await you. Should you have any questions, just email us at support@thecompellingimage.com and we’ll get back to you straight away. 
So, let’s get started!

Regards,
{{ config('app.name') }}
@endcomponent
