<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetUserInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:list {stripe_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get list of invoices';

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
        $stripe_id = $this->argument('stripe_id');
        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
        
        $invoices = \Stripe\Invoice::all(array('limit'=>100, 'customer'=>$stripe_id));
        foreach ($invoices->autoPagingIterator() as $invoice) {
            $paid = $invoice->paid;
            if (is_bool($paid)) {
                if (!$paid) {
                    $paid = "Unpaid";
                } else {
                    $paid = "Paid";
                }
            }
            
            foreach ($invoice->lines->data as $invoice_lines) {
                echo $invoice->id . " [" . $paid . "]\t" . $invoice_lines->plan->id . "\t" . 
                        \Carbon\Carbon::createFromTimestamp($invoice->date)->toDateTimeString()  . "\n";
            }
        }
    }
}
