<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Horizon\Horizon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
	    Horizon::auth(function ($request) {
			$user = $request->user();
			if ($user != NULL) {
				if ($user->admin == 1) {
					return TRUE;
				}
			}
	    });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
	    if ($this->app->environment() !== 'production') {
		    $this->app->register(\Way\Generators\GeneratorsServiceProvider::class);
		    $this->app->register(\Xethron\MigrationsGenerator\MigrationsGeneratorServiceProvider::class);
	    }
    }
}
