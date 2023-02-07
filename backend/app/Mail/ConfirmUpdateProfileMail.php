<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmUpdateProfileMail extends Mailable
{
    use Queueable, SerializesModels;

    private $email;
    private $name;
    private $code;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email,$name,$code)
    {
        $this->email = $email;
        $this->name = $name;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->email)->priority(1)->subject(config('app.name'))->view('emails.profileUpdate',['code'=>$this->code,'name'=>$this->name]);
    }
}
