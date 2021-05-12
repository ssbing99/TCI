@component('mail::message')
# Hello, {{ auth()->user()->name }}

Welcome to the Compelling Image!  <br/><br/>
Thank you for signing up for the {{ $content['title'] }} course. You’ll be receiving a personal greeting from your instructor soon. In the meantime, you can sign in and access your Student Area, where your first lesson and assignment await you. Should you have any questions, just email us at support@thecompellingimage.com and we’ll get back to you straight away. <br/><br/>
So, let’s get started!<br/><br/>

Regards,<br/><br/>
{{ config('app.name') }}
@endcomponent
