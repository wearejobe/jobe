@component('mail::message')

<h1>New invoice</h1>
<br>
<div>Hi <b>{{$bj->name}}</b>,</div>
<br>
<div>{{ $company->name }} have new invoice. You can check your invoices in your account:</div>

@component('mail::button', ['url' => $invoiceslink])
View invoices
@endcomponent
<br>
<br>
<div>The Jobe Team.</div>
<br>
@endcomponent