<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\User;
use App\InstagramProfile;


class MigrateUsers extends Command
{
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
        $master_users = DB::connection('mysql_master')
                ->table('user')
                ->where('partition', $this->argument('partition'))
                ->get();
        
        foreach ($master_users as $master_user) {
            $this->line($master_user);
            $user = new User();
            $user->user_id = $master_user->user_id;
            $user->created_at = $master_user->created_at;
            $user->updated_at = $master_user->updated_at;
            if ($user->save()) {
                dd($user);
            }
        }
    }
}
