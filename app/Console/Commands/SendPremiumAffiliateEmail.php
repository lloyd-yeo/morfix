<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use App\User;
use App\Mail\NewPremiumAffiliate;

class SendPremiumAffiliateEmail extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:affiliatepremium';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to affiliate.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $referrer = User::where('email', 'l-ywz@hotmail.com')->first();
        $referred = User::where('email', 'rongronggg@gmail.com')->first();
        Mail::to($referrer->email)->send(new NewPremiumAffiliate($referrer, $referred));
    }

}
