<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomEmail extends Mailable
{
    use Queueable, SerializesModels;

	public $subject;
	public $content;
	public $morfix_sender_email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $content, $morfix_sender_email)
    {
		$this->subject = $subject;
		$this->content = $content;
		$this->morfix_sender_email = $morfix_sender_email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	    $subject = $this->subject;

	    $headerData = [
		    'category' => '[Custom] [' . $this->morfix_sender_email . ']',
	    ];

	    $header = $this->asString($headerData);

	    $this->withSwiftMessage(function ($message) use ($header) {
		    $message->getHeaders()
		            ->addTextHeader('X-SMTPAPI', $header);
	    });

	    return $this->view('email.custom')
	                ->subject($subject)
	                ->bcc("admin@morfix.co", "Morfix")
	                ->with([ 'text' => $this->content ]);
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
