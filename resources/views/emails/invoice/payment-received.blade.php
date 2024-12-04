@component('mail::message')

<h1>{{ $subject }}</h1>
<br>
<div>Hi <b>{{$pj->name}}</b>,</div>
<br>
<div>You received a payment from <b>{{ $company->name }}</b> for the job <b>{{ $job->title }}</b>. To check your payments go to your job payments page.</div>

@component('mail::button', ['url' => $url])
View job payments
@endcomponent
<br>
<br>
<div>The Jobe Team.</div>
<br>
@endcomponent