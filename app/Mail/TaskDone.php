<?php

namespace App\Mail;

use App;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskDone extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $pj, $bj, $job, $task, $jobtaskslink;
    public function __construct($task,$pj,$bj,$job)
    {
        // 

        $this->pj = $pj;
        $this->job = $job;
        $this->bj = $bj;
        $this->task = $task;
        $this->jobtaskslink = route('job.tasks', ["code"=>md5($job->id) .'_'. $job->id]);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Task is done')->markdown('emails.tasks.taskdone');
    }
}
