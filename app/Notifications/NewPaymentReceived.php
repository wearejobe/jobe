<?php

namespace App\Notifications;

use App;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewPaymentReceived extends Notification
{
    use Queueable;

    public $pj,$bj,$company,$url,$subject;
    public function __construct($payment,$pj)
    {
        //
        $this->pj = $pj;
        $this->company = App\Companies::find($payment->from_company);
        $this->job = App\Jobs::find($payment->job_id);
        $this->subject = 'Payment received';
        
        $this->url = route('job.payments',['code'=>md5($this->job->id) .'_'.$this->job->id]);
    }

    
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)->subject($this->subject)->markdown(
            'emails.invoice.payment-received', 
            [
                'pj' => $this->pj,
                'job' => $this->job,
                'company' => $this->company,
                'subject' => $this->subject,
                'url' => $this->url
            ]
        );
    }
    public function toArray($notifiable)
    {
        return [
            'url' => $this->url,
            'title' => $this->subject,
            'icon' => '<span class="material-icons">payments</span>',
            'description' => 'Received from: ' . $this->company->name,
        ];
    }
}
