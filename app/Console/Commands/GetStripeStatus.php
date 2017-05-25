<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Response;
use App\User;
use App\InstagramProfile;
use App\Niche;
use App\InstagramProfileComment;
use App\InstagramProfileTargetHashtag;
use App\InstagramProfileTargetUsername;
use App\DefaultImageGallery;
use App\UserImages;
use App\InstagramProfilePhotoPostSchedule;
use App\StripeDetail;
use Stripe\Stripe as Stripe;

class GetStripeStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:checkchargespaid';

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
        \Stripe\Stripe::setApiKey("sk_live_HeS5nnfJ5qARMPsANoGw32c2");
        $rows = DB::connection('mysql_old')->select("SELECT referred_email, referrer_email, subscription_id, charge_id, invoice_id
                            FROM insta_affiliate.get_referral_charges_of_user 
                            WHERE charge_created >= \"2017-04-01 00:00:00\"
                            AND charge_created <= \"2017-04-31 23:59:59\"
                            ORDER BY referrer_email ASC 
                            LIMIT 10000;");
        $file = fopen('export.csv', 'w');

        

        
        foreach ($rows as $row) {
            $invoice = \Stripe\Invoice::retrieve($row->invoice_id);
            $charge = \Stripe\Charge::retrieve($invoice->charge);
            $refunded = 0;
            if ($charge->refunded != 1) {
                $refunded = 0;
            } else {
                $refunded = 1;
            }
            fwrite($file, $row->referrer_email . "," . $row->referred_email . "," . $row->subscription_id . "," . $invoice->id . "," . $invoice->paid . ',' . $refunded . "\n");
            $this->line($row->referrer_email . "," . $row->referred_email . "," . $row->subscription_id . "," . $invoice->id . "," . $invoice->paid . ',' . $refunded);
        }
        
        fclose($file);
    }
}
