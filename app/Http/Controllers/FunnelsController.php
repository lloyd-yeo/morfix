<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AWeberAPI;

class FunnelsController extends Controller
{
    public function ebook(){
        return view('funnels.ebook');
    }

	public function ebookVsl(Request $request){

		$consumerKey    = "AkAxBcK3kI1q0yEfgw4R4c77";
		$consumerSecret = "DEchWOGoptnjNSqtwPz3fgZg6wkMpOTWTYCJcgBF";

		$aweber  = new AWeberAPI($consumerKey, $consumerSecret);
		$account = $aweber->getAccount("AgI2J88WjcAhUkFlCn3OwzLx", "wdX1JHuuhIFm9AEiJt3SVUdM5S7Z8lAE7UKmP29P");

		foreach ($account->lists as $offset => $list) {

			$list_id = $list->id;

			if ($list_id != 4798139) {
				continue;
			}

			# create a subscriber
			$params = [
				'email'                             => $request->input("email"),
				'name'                              => $request->input("name"),
				'ip_address'                        => $request->ip(),
				'ad_tracking'                       => 'morfix_ebook',
				'last_followup_message_number_sent' => 1,
				'misc_notes'                        => 'Morfix Ebook',
			];

			try {
				$subscribers    = $list->subscribers;
				$new_subscriber = $subscribers->create($params);
			}
			catch (Exception $ex) {
				$error_msg = $ex->getMessage();
			}
		}

		return view('funnels.ebookvsl');
	}

    public function vsl(){
	    return view('funnels.onlinevsl');
    }

    public function rcVsl(){
	    return view('funnels.rcvsl');
    }

	public function mcaVsl(){
		return view('funnels.mcavsl');
	}

	public function daVsl(){
		return view('funnels.davsl');
	}

	public function ospVsl(){
		return view('funnels.ospvsl');
	}

	public function mmoVsl(){
		return view('funnels.mmovsl');
	}

	public function toolVsl(){
		return view('funnels.toolvsl');
	}

	public function mlmVsl(){
		return view('funnels.mlmvsl');
	}

}
