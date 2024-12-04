<?php

namespace App\Notifications;

use App;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;


class NewWorkingShift extends Notification
{
    use Queueable;

    public $pj,$bj,$url,$task,$job,$subject;
    public function __construct($task,$job,$bj,$interval)
    {
        //
        $this->pj = auth()->user();
        $this->bj = $bj;
        $this->task = $task;
        $this->job = $job;

        $this->subject = 'New working shift started';
        
        $this->url = route('job.tasks',['code'=>md5($this->job->id) .'_'.$this->job->id]);
    }

    
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)->subject($this->subject)->markdown(
            'emails.tasks.newshift', 
            [
                'pj' => $this->pj,
                'job' => $this->job,
                'bj' => $this->bj,
                'task' => $this->task,
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
            'icon' => '<span class="material-icons">watch_later</span>',
            'description' => $this->task->title,
        ];
    }
}
