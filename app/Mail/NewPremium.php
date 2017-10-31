<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class NewPremium extends Mailable {

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
        $subject = '[Morfix] Your Premium account is ready!';

	    $headerData = [
		    'category' => '[New Premium] ' . $this->user->email,
	    ];

	    $header = $this->asString($headerData);

	    $this->withSwiftMessage(function ($message) use ($header) {
		    $message->getHeaders()
			    ->addTextHeader('X-SMTPAPI', $header);
	    });

        return $this->view('email.signup.premium')
                        ->subject($subject)
	                    ->bcc("admin@morfix.co", "Morfix")
                        ->with(['name' => $this->user->name,
                            'email' => $this->user->email,
                            'password' => $this->user->password]);
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
