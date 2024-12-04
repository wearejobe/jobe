<?php

namespace App\Notifications;

use App;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobApplied extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $user,$job,$company;
    public function __construct($job,$companyID)
    {
        //
        $this->user = auth()->user();
        $this->job = $job;
        $this->company = App\Companies::find($companyID);
    }

    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)->subject('You applied for job')->markdown(
            'emails.jobs.jobapplied', 
            [
                'user' => $this->user,
                'job' => $this->job,
                'company' => $this->company
            ]
        );
    }
    public function toArray($notifiable)
    {
        return [
            'url' => route('jobs'),
            'title' => 'You applied for a job',
            'icon' => '<span class="material-icons">check</span>',
            'description' => $this->job->title,
        ];
    }
}
