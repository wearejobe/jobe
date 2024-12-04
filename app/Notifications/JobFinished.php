<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobFinished extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $bj,$subject,$job,$url;
    public function __construct($bj,$jid)
    {
        $this->bj = $bj;
        $this->job = App\Jobs::find($jid);
        $this->subject = 'Congratulations, job has been completed';
        $this->url = route('job.pjDashboard',['code'=>md5($this->job->id) .'_'.$this->job->id]);
    }
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)->subject($this->subject)->markdown(
            'emails.jobs.jobfinished', 
            [
                'bj' => $this->bj,
                'job' => $this->job,
                'subject' => $this->subject
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
