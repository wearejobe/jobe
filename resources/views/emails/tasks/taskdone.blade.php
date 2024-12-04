@component('mail::message')

<h1>Task is done</h1>
<br>
<div>Hi <b>{{$bj->name}}</b>,</div>
<br>
<div><b>{{$pj->name}}</b> mark the task "{{ $task->title }}" as <b>DONE</b>. You can check the work progress of your job <b>{{$job->title}}</b> in the job tasks section:</div>

@component('mail::button', ['url' => $jobtaskslink])
View job tasks
@endcomponent
<br>
<br>
<div>The Jobe Team.</div>
<br>
@endcomponent