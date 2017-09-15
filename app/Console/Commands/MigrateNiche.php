<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Niche;
use App\NicheTarget;
use App\NicheTargetHashtag;

class MigrateNiche extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:niche';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the niche from the master server.';

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
        $master_niches = DB::connection('mysql_master')
                        ->table('niches')
                        ->get();
        
        foreach ($master_niches as $master_niche) {
            $niche = new Niche();
            $niche->niche_id = $master_niche->niche_id;
            $niche->niche = $master_niche->niche;
            $niche->save();
        }
        
        $master_niches_usernames = DB::connection('mysql_master')
                        ->table('niche_targets')
                        ->get();
        
        foreach ($master_niches_usernames as $master_niches_username) {
            $niches_username = new NicheTarget();
            $niches_username->id = $master_niches_username->id;
            $niches_username->niche_id = $master_niches_username->niche_id;
            $niches_username->target_username = $master_niches_username->target_username;
            $niches_username->save();
        }
        
        $master_niches_hashtags = DB::connection('mysql_master')
                        ->table('niche_targets_hashtags')
                        ->get();
        
        foreach ($master_niches_hashtags as $master_niches_hashtag) {
            $niches_hashtag = new NicheTargetHashtag();
            $niches_hashtag->id = $master_niches_hashtag->id;
            $niches_hashtag->niche_id = $master_niches_hashtag->niche_id;
            $niches_hashtag->hashtag = $master_niches_hashtag->hashtag;
            $niches_hashtag->save();
        }
    }
}
