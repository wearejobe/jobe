<?php

namespace App\Notifications;

use App;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskDone extends Notification
{
    use Queueable;

    public $pj, $bj, $job, $task, $jobtaskslink;
    public function __construct($task,$pj,$bj,$job)
    {

        $this->pj = $pj;
        $this->job = $job;
        $this->bj = $bj;
        $this->task = $task;
        $this->jobtaskslink = route('job.tasks', ["code"=>md5($job->id) .'_'. $job->id]);
    }

    
    public function via($notifiable)
    {
        return ['mail','database'];
    }

    
    public function toMail($notifiable)
    {
        return (new MailMessage)->subject('Task is done')->markdown(
            'emails.tasks.taskdone', 
            [
                'pj' => $this->pj,
                'job' => $this->job,
                'bj' => $this->bj,
                'task' => $this->task,
                'jobtaskslink' => $this->jobtaskslink
            ]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'url' => $this->jobtaskslink,
            'title' => 'Task done!',
            'icon' => '<span class="material-icons">check</span>',
            'description' => $this->task->title,
        ];
    }
}
