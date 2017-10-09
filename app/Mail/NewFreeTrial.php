<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class NewFreeTrial extends Mailable {

    use Queueable,
        SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user) {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $subject = '[Morfix] Your Free Trial account is ready!';
        return $this->view('email.signup.freetrial')
                        ->subject($subject)
                        ->with(['name' => $this->user->name,
                            'email' => $this->user->email,
                            'password' => $this->user->password]);
    }

}
