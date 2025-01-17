<?php

namespace App\Providers;

use App\Exceptions\UserAuthorizationException;
use Illuminate\Support\Facades\Gate;
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
        Gate::define('view.all.films', function ($user) {
            if (!$user->can('view.films') && !$user->hasRole('admin')) {
                throw new UserAuthorizationException('No tienes permiso para consultar peliculas.');
            }

            return true;
        });

        Gate::define('view.all.vehicles', function ($user) {
            if (!$user->can('view.vehicles') && !$user->hasRole('admin')) {
                throw new UserAuthorizationException('No tienes permiso para consultar vehículos.');
            }

            return true;
        });
    }
}
