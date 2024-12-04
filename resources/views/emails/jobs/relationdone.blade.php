@component('mail::message')

<h1>{{ $subject }}</h1>
<br>
<div>Hi <b>{{$bj->name}}</b>,</div>
<br>
<div>The relation step of your job "{{ $job->title }}" is progressing according to our Global Metodology.</div>
<br>
<table>
    <tr>
        <td><img src="http://app.wearejobe.com/images/job-stages/brief.png"></td>
        <td><img src="http://app.wearejobe.com/images/job-stages/relation.png"></td>
        <td><img src="http://app.wearejobe.com/images/job-stages/explore-meeting-off.png"></td>
        <td><img src="http://app.wearejobe.com/images/job-stages/deliverables-off.png"></td>
    </tr>
</table>
<div>Lear more about <b>Global Metodology</b> in our website <a href="https://wearejobe.com/metodologia/">wearejobe.com</a>.</div>
<br>
<div>The Jobe Team.</div>
<br>
@endcomponent