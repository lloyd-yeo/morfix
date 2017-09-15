<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\User;
use App\InstagramProfile;

class MigrateUsers extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:user {partition}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate User, InstagramProfile to the target table.';

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
        $master_users = DB::connection('mysql_master')
                ->table('user')
                ->where('partition', $this->argument('partition'))
                ->get();

        foreach ($master_users as $master_user) {
            
            $this->line($master_user);
            $user = new User();
            
            $user->user_id = $master_user->user_id;
            $user->email = $master_user->email;
            $user->password = $master_user->password;
            $user->num_acct = $master_user->num_acct;
            $user->last_login = $master_user->last_login;
            $user->active = $master_user->active;
            $user->verification_token = $master_user->verification_token;
            $user->timezone = $master_user->timezone;
            $user->stripe_id = $master_user->stripe_id;
            $user->user_tier = $master_user->user_tier;
            $user->premium_pro = $master_user->premium_pro;
            $user->biz_pro = $master_user->biz_pro;
            $user->name = $master_user->name;
            $user->trial_activation = $master_user->trial_activation;
            $user->trial_end_date = $master_user->trial_end_date;
            $user->close_dm_tut = $master_user->close_dm_tut;
            $user->close_dashboard_tut = $master_user->close_dashboard_tut;
            $user->close_interaction_tut = $master_user->close_interaction_tut;
            $user->close_profile_tut = $master_user->close_profile_tut;
            $user->close_scheduling_tut = $master_user->close_scheduling_tut;
            $user->ref_keyword = $master_user->ref_keyword;
            $user->paypal_email = $master_user->paypal_email;
            $user->all_time_commission = $master_user->all_time_commission;
            $user->pending_commission = $master_user->pending_commission;
            $user->tier = $master_user->tier;
            $user->admin = $master_user->admin;
            $user->vip = $master_user->vip;
            $user->created_at = $master_user->created_at;
            $user->updated_at = $master_user->updated_at;
            $user->engagement_quota = $master_user->engagement_quota;
            $user->remember_token = $master_user->remember_token;
            $user->pending_commission_payable = $master_user->pending_commission_payable;
            $user->paypal = $master_user->paypal;
            $user->last_pay_out_date = $master_user->last_pay_out_date;
            $user->partition = $master_user->partition;
            
            if ($user->save()) {
                dd($user);
            }
        }
    }

}
