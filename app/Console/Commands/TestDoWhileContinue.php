<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestDoWhileContinue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:dowhilecontinue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the behaviour of continue in a do-while loop.';

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
        $next_max_id = NULL;
        
        
        do {
            
            $exists = false;
            
            $next_max_id = rand(0,1);
            if ($next_max_id === 1) {
                $next_max_id = NULL;
            }
            
            $a = rand(0,1);
            $b = rand(0,1);
            echo $a . "\t" . $b;
            if ($a == $b) {
                continue;
            }
            
            
            
        } while ($next_max_id !== NULL);
    }
}
