<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\PaymentFailed;
use Illuminate\Support\Facades\Mail;

class SendFailedPaymentEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:failedpayment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Failed Payment Email';

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
        Mail::to("l-ywz@hotmail.com")->send(new PaymentFailed());
    }
}
