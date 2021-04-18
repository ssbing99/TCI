<?php

namespace App\Mail\Frontend;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StudentMeetingMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var
     */
    public $content;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.studentMeetingMail')
            ->subject('Zoom Meeting Invitation '.env('APP_NAME'))
            ->with('content',$this->content);
    }
}
