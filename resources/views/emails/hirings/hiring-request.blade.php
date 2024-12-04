@component('mail::message')

<h1>Congratulations, You are hired</h1>
<br>
<div>Hi <b>{{$pj->name}}</b>,</div>
<br>
<div>Your application for the job "{{ $job->title }}" has been seen and you are hired to work with <b>{{$company->name}}</b>.</div>
<br>
<div>Go to your account and accept the hiring request in your application.</div>
<br>
@component('mail::button', ['url' => $applicationlink])
View application
@endcomponent
<br>
<div>The Jobe Team.</div>
<br>
@endcomponent