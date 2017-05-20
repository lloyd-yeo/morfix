<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MakeStripeIdActive extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:active';

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
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $stripe_id_rows = DB::connection('mysql_old')->select('SELECT email, stripe_id, COUNT(*) c '
                . 'FROM user_stripe_details GROUP BY email, stripe_id HAVING c = 1 LIMIT 0, 10000;');
        foreach ($stripe_id_rows as $stripe_id_row) {
            $email = $stripe_id_row->email;
            $stripe_id = $stripe_id_row->stripe_id;
            try {
                if (DB::connection('mysql_old')->update("UPDATE user SET stripe_id = ? WHERE email = ?;", [$stripe_id, $email])) {
                    $this->line("Updated [" . $email . "]");
                }
            } catch (Exception $ex) {
                $this->line($ex->getMessage());
            }
        }
    }

}
