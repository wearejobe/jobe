@component('mail::message')

<h1>You received a job application</h1>
<br>
<div>Hi <b>{{$bj->name}}</b>,</div>
<br>
<div>Your company <b>{{$company->name}}</b> has been received application for the job "{{ $job->title }}".</div>

@component('mail::button', ['url' => $contractlink])
View Application
@endcomponent
<br>
<br>
<div>The Jobe Team.</div>
<br>
@endcomponent