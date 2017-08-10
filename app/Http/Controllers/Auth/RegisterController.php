<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use AWeberAPI;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\UserAffiliates;

class RegisterController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Register Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users as well as their
      | validation and creation. By default this controller uses a trait to
      | provide this functionality without requiring any additional code.
      |
     */

use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255|unique:user',
                    'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data) {
        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->tier = 1;
        $user->num_acct = 1;
        $user->trial_activation = 1;
        
        if ($user->save()) {
            
            $referrer = $data['referrer'];
            
            $user_affiliate = new UserAffiliates;
            $user_affiliate->referrer = $referrer;
            $user_affiliate->referred = $user->user_id;
            $user_affiliate->save();
            
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
            
        }

        return $user;

        #return User::create([
        #            'name' => $data['name'],
        #            'email' => $data['email'],
        #            'password' => $data['password'],
        #            'verification_token' => Uuid::generate(),
//      #      'password' => bcrypt($data['password']),
        #]);
    }

}
