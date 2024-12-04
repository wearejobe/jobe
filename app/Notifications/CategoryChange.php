<?php

namespace App\Notifications;

use App;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CategoryChange extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $pj,$pjcategory,$subject,$url;
    public function __construct($pj,$pjcategory)
    {
        $this->pj = $pj;
        $this->pjcategory = App\PjCategories::find($pjcategory);
        $this->subject = 'Your rank has been changed';
        $this->url = route('profile');
    }

    public function via($notifiable)
    {
        return ['mail','database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)->subject($this->subject)->markdown(
            'emails.rating.rankchange', 
            [
                'subject' => $this->subject,
                'pj' => $this->pj,
                'pjcategory' => $this->pjcategory
            ]
        );
    }
    public function toArray($notifiable)
    {
        return [
            'url' => $this->url,
            'title' => $this->subject,
            'icon' => '<span class="material-icons">check</span>',
            'description' => $this->pjcategory->name,
        ];
    }
}
