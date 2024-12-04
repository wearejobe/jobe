<?php

namespace App\Notifications;

use App;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HiringResponse extends Notification
{
    use Queueable;

    
    public $pj,$bj,$company,$jobdashboardlink,$subject;
    public function __construct($hiring,$bj)
    {
        //
        $this->pj = App\User::find($hiring->applicant_id);
        $this->bj = $bj;
        $this->job = App\Jobs::find($hiring->job_id);
        $this->subject = $this->pj->name . ' accepted your hiring request';
        
        $this->jobdashboardlink = route('job.pjDashboard',['code'=>md5($this->job->id) .'_'.$this->job->id]);
    }

    
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)->subject($this->subject)->markdown(
            'emails.hirings.hiring-response', 
            [
                'pj' => $this->pj,
                'bj' => $this->bj,
                'job' => $this->job,
                'company' => $this->company,
                'subject' => $this->subject,
                'jobdashboardlink' => $this->jobdashboardlink
            ]
        );
    }
    public function toArray($notifiable)
    {
        return [
            'url' => $this->jobdashboardlink,
            'title' => $this->subject,
            'icon' => '<span class="material-icons">check</span>',
            'description' => $this->job->title,
        ];
    }
}
