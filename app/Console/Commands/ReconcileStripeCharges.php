<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\StripeCharge;

class ReconcileStripeCharges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:reconcilecharge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $wrong_charges = StripeCharge::whereNull('invoice_id')->get();
        foreach ($wrong_charges as $wrong_charge) {
            \Stripe\Stripe::setApiKey("sk_live_gnfRoHfQNhreT79YP9b4mIoB");
            $charge = \Stripe\Charge::retrieve($wrong_charge->charge_id);
            $customer_id = $charge->customer;
            $wrong_charge->stripe_id = $customer_id;
            echo $charge;
//            exit;
        }
    }
}
