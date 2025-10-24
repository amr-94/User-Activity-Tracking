<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Interfaces\BaseRepositoryInterface;
use App\Models\{User, IdleSession, Penalty};
use App\Observers\{UserObserver, IdleSessionObserver, PenaltyObserver};
use App\Repositories\{
    ActivityRepository,
    UserRepository,
    IdleRepository,
    PenaltyRepository,
    SettingRepository
};
use App\Http\Controllers\Admin\{
    ActivityController,
    UserController,
    IdleController,
    PenaltyController,
    SettingController
};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {
        $this->app->when(ActivityController::class)
            ->needs(BaseRepositoryInterface::class)
            ->give(ActivityRepository::class);

        $this->app->when(UserController::class)
            ->needs(BaseRepositoryInterface::class)
            ->give(UserRepository::class);

        $this->app->when(IdleController::class)
            ->needs(BaseRepositoryInterface::class)
            ->give(IdleRepository::class);

        $this->app->when(PenaltyController::class)
            ->needs(BaseRepositoryInterface::class)
            ->give(PenaltyRepository::class);
        $this->app->when(SettingController::class)
            ->needs(BaseRepositoryInterface::class)
            ->give(SettingRepository::class);
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        // Register Observers
        User::observe(UserObserver::class);
        IdleSession::observe(IdleSessionObserver::class);
        Penalty::observe(PenaltyObserver::class);
    }
}
