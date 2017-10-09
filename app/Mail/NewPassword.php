<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class NewPassword extends Mailable
{
    use Queueable, SerializesModels;
    
    public $user;
    public $mode;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $mode)
    {
        $this->user = $user;
        $this->mode = $mode;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "";
        if ($this->mode == "free_trial") {
            $subject = '[Morfix] Your Free Trial account is ready!';
        } else if ($this->mode == "premium") {
            $subject = '[Morfix] Your Premium account is ready!';
        }
        
        return $this->view('email.signup.password')
                    ->subject($subject)
                    ->with(['name' => $this->user->name, 
                        'email' => $this->user->email, 
                        'password' => $this->user->password,
                        'mode' => $this->mode]);
        
    }
}
