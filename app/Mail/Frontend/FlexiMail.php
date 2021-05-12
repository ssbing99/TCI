<?php

namespace App\Mail\Frontend;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FlexiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $content, $template, $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content, $template, $subject)
    {
        $this->content = $content;
        $this->template = $template;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails/'.$this->template)
            ->subject($this->subject.env('APP_NAME'))
            ->with('content',$this->content);
    }
}
