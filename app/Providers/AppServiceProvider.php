<?php

namespace App\Providers;

use App\Models\Player;
use App\Models\Team;
use App\Observers\PlayerObserver;
use App\Observers\TeamObserver;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Player::observe(PlayerObserver::class);
        Team::observe(TeamObserver::class);
    }
}
