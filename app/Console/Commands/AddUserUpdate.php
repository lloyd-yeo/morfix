<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\UserUpdate;

class AddUserUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'system:update {type} {title} {message}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a global wide announcement on the update board';

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
        $type = $this->argument('type');
        $title = $this->argument('title');
        $message = $this->argument('message');
        
        $users = User::all();
        foreach ($users as $user) {
            $update = new UserUpdate;
            $update->email = $user->email;
            $update->title = $title;
            $update->type = $type;
            $update->message = $message;
            $update->save();
        }
    }
}
