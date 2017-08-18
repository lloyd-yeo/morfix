<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AWeberAPI;
use App\User;

class FunnelWebhookController extends Controller {

    public function test() {
        return response('Hello World', 200);
    }

    public function contactCreated() {
        return response('Contact created', 200);
    }

    public function contactUpdated() {
        return response('Contact Updated', 200);
    }

    public function purchaseCreated() {
        return response('Purchase created', 200);
    }

    public function purchaseUpdated() {
        return response('Purchase Updated', 200);
    }

    public function freeTrialCustomerCreated(Request $request) {

        $payload = $request->json();
        $customer = $payload->contact;

        $user = new User;
        $user->email = $customer->email;
        $user->name = $customer->name;
        $user->trial_activation = 1;
        $user->trial_end_date = \Carbon\Carbon::now()->addWeek();
        $user->password = str_random(8);
        $user->num_acct = 1;
        $user->active = 1;
        $user->verification_token = str_random(20);
        $user->user_tier = 1;
        $user->tier = 1;
        
        if ($user->save()) {
            $consumerKey = "AkAxBcK3kI1q0yEfgw4R4c77";
            $consumerSecret = "DEchWOGoptnjNSqtwPz3fgZg6wkMpOTWTYCJcgBF";

            $aweber = new AWeberAPI($consumerKey, $consumerSecret);
            $account = $aweber->getAccount("AgI2J88WjcAhUkFlCn3OwzLx", "wdX1JHuuhIFm9AEiJt3SVUdM5S7Z8lAE7UKmP29P");

            foreach ($account->lists as $offset => $list) {
                $list_id = $list->id;
                
                if ($list_id != 4485376) {
                    continue;
                }

                # create a subscriber
                $params = array(
                    'email' => $data['email'],
                    'name' => $data['name'],
                    'ip_address' => \Request::ip(),
                    'ad_tracking' => 'morfix_registration',
                    'last_followup_message_number_sent' => 1,
                    'misc_notes' => 'MorifX Registration Page'
                );

                try {
                    $subscribers = $list->subscribers;
                    $new_subscriber = $subscribers->create($params);
                } catch (\AWeberAPIException $ex) {
                    echo $ex->getMessage();
                }
            }
            return response('[' . $customer->email . '] Free Trial Customer Updated', 200);
        }
    }

    public function freeTrialCustomerUpdated() {
        return response('Free Trial Customer Updated', 200);
    }

}
