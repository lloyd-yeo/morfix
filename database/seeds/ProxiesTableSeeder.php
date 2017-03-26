<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProxiesTableSeeder extends Seeder {

    private $proxy_file = "proxy.list";

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        
        $proxies = file(base_path() . "/database/seeds/" . $this->proxy_file);
        
        foreach ($proxies as $proxy) {
            DB::table('proxies')->insert([
                'proxy' => 'http://' . $proxy,
                'assigned' => 0,
            ]);
        }
    }

}
