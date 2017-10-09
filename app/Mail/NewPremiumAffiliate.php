<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewPremiumAffiliate extends Mailable
{
    use Queueable, SerializesModels;
    
    public $referrer;
    public $referred;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $referrer, User $referred)
    {
        $this->referrer = $referrer;
        $this->referred = $referred;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = '[Morfix] More Cash!';
        return $this->view('email.affiliate.premium')
                        ->subject($subject)
                        ->with(['referrer_name' => $this->referrer->name,
                            'referred_email' => $this->referred->email]); 
    }
}
