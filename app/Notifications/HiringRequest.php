<?php

namespace App\Notifications;

use App;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HiringRequest extends Notification
{
    use Queueable;

    public $pj,$job,$company,$applicationlink;
    public function __construct($hiring,$pj,$application)
    {
        //
        $this->pj = $pj;
        $this->job = App\Jobs::find($hiring->job_id);
        $this->company = App\Companies::find($this->job->company_id);
        $this->applicationlink = route('contract.view',['id'=>md5($application->id) .'_'.$application->id]);
    }

    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)->subject('Congratulations, You are hired')->markdown(
            'emails.hirings.hiring-request', 
            [
                'pj' => $this->pj,
                'job' => $this->job,
                'company' => $this->company,
                'applicationlink' => $this->applicationlink
            ]
        );
    }
    public function toArray($notifiable)
    {
        return [
            'url' => $this->applicationlink,
            'title' => 'Congratulations, You are hired',
            'icon' => '<span class="material-icons">check</span>',
            'description' => $this->job->title,
        ];
    }
}
