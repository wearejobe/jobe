<?php

namespace App\Mail;

use App;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewInvoice extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public  $bj, $company, $invoiceslink;
    public function __construct($bj,$company)
    {
        // 
        $this->bj = $bj;
        $this->company = $company;
        $this->invoiceslink = route('payment.receipts');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Invoice')->markdown('emails.invoice.newinvoice');
    }
}
