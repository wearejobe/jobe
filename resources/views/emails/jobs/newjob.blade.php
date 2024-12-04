@component('mail::message')

<h1>You just created new job</h1>
<br>
<div>Hi <b>{{$user->name}}</b>,</div>
<br>
<div>You have published the job <b>{{ $job->title }}</b>. Now this job is avaialabe to the public and you can receive applications from Professional Jobe workers and start hiring.</div>
<br>
<hr>
<br>
<div>The first step of this job was complete, bellow the job progress according to our Global Metodology.</div>
<br>
<table>
    <tr>
        <td style="width:25%;"><img src="http://app.wearejobe.com/images/job-stages/brief.png"></td>
        <td style="width:25%;"><img src="http://app.wearejobe.com/images/job-stages/relation-off.png"></td>
        <td style="width:25%;"><img src="http://app.wearejobe.com/images/job-stages/explore-meeting-off.png"></td>
        <td style="width:25%;"><img src="http://app.wearejobe.com/images/job-stages/deliverables-off.png"></td>
    </tr>
    <tr style="text-align: center;">
        <td width="25%"><h6>BRIEF</h6></td>
        <td width="25%"><h6>RELATION</h6></td>
        <td width="25%"><h6>MEETING</h6></td>
        <td width="25%"><h6>DELIVERABLES</h6></td>
    </tr>
</table>
<br>
<div>The Jobe Team.</div>
<br>
@endcomponent