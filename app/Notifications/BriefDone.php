<?php

namespace App\Notifications;

use App;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BriefDone extends Notification
{
    use Queueable;

    public $subject, $bj, $job;
    public function __construct($bj,$job_id)
    {
        $this->bj = $bj;
        $this->job = App\Jobs::find($job_id);
        $this->subject = 'Congratulations the first step is done';
    }

    public function via($notifiable)
    {
        return [];
    }
    public function viaQueues()
    {
        return [
            'mail' => 'mail-queue'
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)->subject($this->subject)->markdown(
            'emails.jobs.briefdone', 
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
