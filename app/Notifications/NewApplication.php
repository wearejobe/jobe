<?php

namespace App\Notifications;

use App;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApplication extends Notification
{
    use Queueable;

   
    public  $bj,
            $job,
            $contractlink,
            $company;
    public function __construct($job,$bj,$app)
    {
        //
        $this->bj = $bj;
        $this->job = $job;
        $this->contractlink = route('contract.view',['id'=>md5($app->id) .'_'. $app->id]);
        $this->company = App\Companies::find($job->company_id);
    }

    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)->subject('New application received')->markdown(
            'emails.jobs.newapplication', 
            [
                'bj' => $this->bj,
                'job' => $this->job,
                'company' => $this->company,
                'contractlink' => $this->contractlink
            ]
        );
    }
    public function toArray($notifiable)
    {
        return [
            'url' => $this->contractlink,
            'title' => 'New application received',
            'icon' => '<span class="material-icons">check</span>',
            'description' => $this->job->title,
        ];
    }
}
