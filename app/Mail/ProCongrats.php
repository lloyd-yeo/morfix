<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class ProCongrats extends Mailable
{
    use Queueable, SerializesModels;
    
    public $user;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "[Morfix] Congrats! You are officially a Pro user now!";
        
        return $this->view('email.upgrade.pro')
                    ->subject($subject)
                    ->with(['name' => $this->user->name]);
    }
}
