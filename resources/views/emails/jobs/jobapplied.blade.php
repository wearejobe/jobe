@component('mail::message')

<h1>You applied for job</h1>
<br>
<div>Hi <b>{{$user->name}}</b>,</div>
<br>
<div>Your application for the job "{{ $job->title }}" has been sent to <b>{{$company->name}}</b>.</div>
<br>
<div>We will let you know when <b>{{ $company->name }}</b> responds to your application.</div>
<br>
<div>The Jobe Team.</div>
<br>
@endcomponent