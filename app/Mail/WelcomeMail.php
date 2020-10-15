<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    private $isTeacher = false;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $teacher)
    {
        $this->user = $user;
        $this->isTeacher = $teacher;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $emailPath = 'emails/userWelcomeEmail';

        if($this->isTeacher)
            $emailPath = 'emails/teacherWelcomeEmail';

        return $this->markdown($emailPath)
            ->subject('Welcome to '.env('APP_NAME'))
            ->with('user',$this->user);
    }
}
