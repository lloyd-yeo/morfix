<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Proxy;
use DB;

class MigrateProxyList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:proxy';

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
        $proxies = DB::connection('mysql_master')
                ->table('proxy')
                ->get();
        
        foreach ($proxies as $proxy) {
            $new_proxy = new Proxy;
            $new_proxy->proxy = $proxy->proxy;
            $new_proxy->assigned = $proxy->assigned;
            $new_proxy->error = $proxy->error;
            if ($new_proxy->save()) {
                $this->line("Migrated [" . $new_proxy->proxy . "]");
            }
        }
        
    }
}
