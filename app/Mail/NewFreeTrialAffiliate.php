<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class NewFreeTrialAffiliate extends Mailable
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
	    $subject = '[Morfix] New Free-Trial Referral!';

	    $headerData = [
		    'category' => '[Free-Trial Affiliate] ' . $this->referrer->email,
	    ];

	    $header = $this->asString($headerData);

	    $this->withSwiftMessage(function ($message) use ($header) {
		    $message->getHeaders()
		            ->addTextHeader('X-SMTPAPI', $header);
	    });

	    return $this->view('email.affiliate.freetrial')
	                ->subject($subject)
	                ->bcc("admin@morfix.co", "Morfix")
	                ->with([ 'referrer_name'  => $this->referrer->name,
	                         'referred_email' => $this->referred->email ]);
    }

	private function asJSON($data)
	{
		$json = json_encode($data);
		$json = preg_replace('/(["\]}])([,:])(["\[{])/', '$1$2 $3', $json);

		return $json;
	}


	private function asString($data)
	{
		$json = $this->asJSON($data);

		return wordwrap($json, 76, "\n   ");
	}
}
