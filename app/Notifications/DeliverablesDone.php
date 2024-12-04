<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliverablesDone extends Notification
{
    use Queueable;


    public $subject, $bj, $job;
    public function __construct($bj,$job_id)
    {
        $this->bj = $bj;
        $this->job = App\Jobs::find($job_id);
        $this->subject = 'Congratulations your project is complete';
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)->subject($this->subject)->markdown(
            'emails.jobs.deliverablesdone', 
            [
                'subject' => $this->subject,
                'bj' => $this->bj,
                'job' => $this->job
            ]
        );
    }
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
