@component('mail::message')
# Hello, {{ $user->first_name }}

# Welcome to {{env('APP_NAME')}}.<br>

<p>
    School Of Permaculture is here to provide a very suitable teaching environment for you,
    you can explore a lot wonderful contents and share your thoughts in our platform, we wish you do enjoyed !!
</p> <br/>


Sincerely,<br>
{{ config('app.name') }}
@endcomponent
