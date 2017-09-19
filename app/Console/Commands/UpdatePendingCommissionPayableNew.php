<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UpdatePendingCommissionPayableNew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:payablenew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update payable commissions for everyone in the csv, and everyone not in';

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
        $users = User::where('tier','>=',2)
                ->get();
        
        foreach ($users as $user){
            $recent_pay_out_date = \Carbon('25th of August');
            
            if($user->last_pay_out_date = $recent_pay_out_date){
                $user->pending_commission_payable
            }
        }
    }
}
