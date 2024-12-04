<?php

namespace App\Mail;
use App;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobApplied extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
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

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('You applied for job')->markdown('emails.jobs.jobapplied');
    }
}
