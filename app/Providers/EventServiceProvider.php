<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UsersInteractionsFailed' => [
            'App\Listeners\LogUsersInteractionsFailed',
        ],
        'App\Events\UserInteractionsFailed' => [
            'App\Listeners\LogUserInteractionsFailed'
        ],
        'App\Events\SlaveUsersInteractionsFailed' => [
            'App\Listeners\LogSlaveUsersInteractionsFailed',
        ],
        'App\Events\EngagementGroupFailed' => [
	        'App\Listeners\LogEngagementGroupFailed',
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
