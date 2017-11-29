<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\User;

class MassMailerController extends Controller
{
    public function sendEmailToActiveUsers(Request $request) {
	    Mail::send(['text' => 'email.custom'], [ 'text' => $request->input("text") ], function ($message) {
		    $message->to('ywz.lloyd@gmail.com');
	    });
    }
}
