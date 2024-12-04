<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HiringRequest extends Mailable
{
    use Queueable, SerializesModels;

    public $pj,$job,$company,$applicationlink;
    public function __construct($hiring,$pj,$application)
    {
        //
        $this->pj = $pj;
        $this->job = App\Jobs::find($hiring->job_id);
        $this->company = App\Companies::find($this->job->company_id);
        $this->applicationlink = route('contract.view',['id'=>md5($application->id) .'_'.$application->id]);
    }

    
    public function build()
    {
        return $this->subject('Congratulations, You are hired')->markdown('emails.hirings.hiring-request');
    }
}
