@component('mail::message')

<h1>{{ $subject }}</h1>
<br>
<div>Hi <b>{{$bj->name}}</b>,</div>
<br>
<div>The next step of your job "{{ $job->title }}" is to have a meeting to explore ideas for your project.</div>
<br>
<table>
    <tr>
        <td style="width:25%;"><img src="http://app.wearejobe.com/images/job-stages/brief.png"></td>
        <td style="width:25%;"><img src="http://app.wearejobe.com/images/job-stages/relation.png"></td>
        <td style="width:25%;"><img src="http://app.wearejobe.com/images/job-stages/explore-meeting.png"></td>
        <td style="width:25%;"><img src="http://app.wearejobe.com/images/job-stages/deliverables-off.png"></td>
    </tr>
</table>
<div>Lear more about <b>Global Metodology</b> in our website <a href="https://wearejobe.com/metodologia/">wearejobe.com</a>.</div>
<br>
<div>The Jobe Team.</div>
<br>
@endcomponent