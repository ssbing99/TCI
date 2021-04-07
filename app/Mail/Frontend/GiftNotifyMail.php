<?php

namespace App\Mail\Frontend;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GiftNotifyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $content, $user, $gift;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content, $user, $gift)
    {
        $this->content = $content;
        $this->user = $user;
        $this->gift = $gift;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails/giftNotifyMail',['user' => $this->user, 'gift' => $this->gift])
            ->subject('Course Gift '.env('APP_NAME'))
            ->with('content',$this->content);
    }
}
