@component('mail::message')

<h1>{{ $subject }}</h1>
<br>
<div>Hi <b>{{$pj->name}}</b>,</div>
<br>
<div>Your rank has changed, your new rank is <b>{{ $pjcategory->name }}.</b></div>
<br>
<br>
<div>The Jobe Team.</div>
<br>
@endcomponent