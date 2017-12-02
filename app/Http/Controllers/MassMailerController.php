<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\User;
use App\Mail\CustomEmail;
use Auth;

class MassMailerController extends Controller
{
    public function sendEmailToActiveUsers(Request $request) {
    	$subject = $request->subject;
    	$text = $request->text;

    	$active_users = User::where("tier", '>', 1)
	                        ->orderBy('created_at', 'desc')
	                        ->get();
		foreach ($active_users as $active_user) {
			Mail::to($active_user)->send(new CustomEmail($subject, $text, Auth::user()->email));
		}
    }
}
