<?php

namespace App\Mail;
use App;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewApplication extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
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

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New application received')->markdown('emails.jobs.newapplication');
    }
}
