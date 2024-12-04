@component('mail::message')

<h1>{{ $subject }}</h1>
<br>
<div>Hi <b>{{$bj->name}}</b>,</div>
<br>
<div>Your hiring request for "{{ $job->title }}" has been accepted, now you can start to work.</div>
<br>
<div>Go to your account and access to job/project dashboard</div>
<br>
@component('mail::button', ['url' => $jobdashboardlink])
View job dashboard
@endcomponent
<br>
<div>The relation step of this job was complete, bellow the job progress according to our Global Metodology.</div>
<br>
<table>
    <tr>
        <td style="width:25%;"><img src="http://app.wearejobe.com/images/job-stages/brief.png"></td>
        <td style="width:25%;"><img src="http://app.wearejobe.com/images/job-stages/relation.png"></td>
        <td style="width:25%;"><img src="http://app.wearejobe.com/images/job-stages/explore-meeting-off.png"></td>
        <td style="width:25%;"><img src="http://app.wearejobe.com/images/job-stages/deliverables-off.png"></td>
    </tr>
</table>
<div>Lear more about <b>Global Metodology</b> in our website <a href="https://wearejobe.com/metodologia/">wearejobe.com</a>.</div>
<br>
<div>The Jobe Team.</div>
<br>
@endcomponent