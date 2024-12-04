<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RelationDone extends Notification
{
    use Queueable;

    public $subject, $bj, $job, $url;
    public function __construct($bj,$job_id)
    {
        $this->bj = $bj;
        $this->job = App\Jobs::find($job_id);
        $this->subject = 'The relation step of your project is done';
        $this->url = route('job.pjDashboard',['code'=>$job->id]);
    }

    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)->subject($this->subject)->markdown(
            'emails.jobs.relationdone', 
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
            'url' => $this->url,
            'title' => $this->subject,
            'icon' => '<span class="material-icons">check</span>',
            'description' => $this->job->title,
        ];
    }
}