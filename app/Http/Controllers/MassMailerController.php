<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\User;
use App\Mail\CustomEmail;

class MassMailerController extends Controller
{
    public function sendEmailToActiveUsers(Request $request) {

    	$subject = $request->subject;
    	$text = $request->text;

	    Mail::to("ywz.lloyd@gmail.com")->send(new CustomEmail($subject, $text, Auth::user()->email));

//    	return $text;

//	    Mail::send(['text' => 'email.custom'], [ 'text' => $request->input("text") ], function ($message) {
//		    $message->to('ywz.lloyd@gmail.com');
//	    });
    }
}
