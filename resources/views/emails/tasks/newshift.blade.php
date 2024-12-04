@component('mail::message')

<h1>{{ $subject }}</h1>
<br>
<div>Hi <b>{{$bj->name}}</b>,</div>
<br>
<div><b>{{$pj->name}}</b> started a new working shift for the task <b>{{ $task->title }}</b> in the job <b>{{ $job->title }}</b>.</div>

@component('mail::button', ['url' => $url])
View job tasks
@endcomponent
<br>
<br>
<div>The Jobe Team.</div>
<br>
@endcomponent