<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use App\User;
use App\Mail\NewPassword;

class SendPremiumEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:premium {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a user a welcome email for the Premium package.';

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
        $email = $this->argument("email");
        $user = User::where('email', $email)->first();
        if ($user === NULL) {
           $this->error("[" . $email . "] user not found"); 
        } else {
            Mail::to($user->email)->send(new NewPassword($user, "premium"));
        }
    }
}
