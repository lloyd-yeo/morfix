<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\StripeDetail;

class RefreshStripeCustomerDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh Stripe Details.';

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
        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
        $customers = \Stripe\Customer::all(array("limit" => 3));
        foreach ($customers->data as $customer) {
            $customer_id = $customer->id;
            $customer_email = $customer->email;
            $stripe_detail = StripeDetail::find($customer_id);
            if ($stripe_detail !== NULL) {
                $stripe_detail->email = $customer_email;
            } else {
                $stripe_detail = new StripeDetail;
                $stripe_detail->stripe_id = $customer_id;
                $stripe_detail->email = $customer_email;
            }
            $stripe_detail->save();
        }
    }
}
