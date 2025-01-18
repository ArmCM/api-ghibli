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

        Gate::define('show.detail.films', function ($user) {
            if (!$user->can('show.films') && !$user->hasRole('admin')) {
                throw new UserAuthorizationException('No tienes permiso para consultar detalle de peliculas.');
            }

            return true;
        });

        Gate::define('view.all.vehicles', function ($user) {
            if (!$user->can('view.vehicles') && !$user->hasRole('admin')) {
                throw new UserAuthorizationException('No tienes permiso para consultar vehículos.');
            }

            return true;
        });

        Gate::define('view.detail.vehicles', function ($user) {
            if (!$user->can('show.vehicles') && !$user->hasRole('admin')) {
                throw new UserAuthorizationException('No tienes permiso para consultar detalle de vehículos.');
            }

            return true;
        });

        Gate::define('view.all.people', function ($user) {
            if (!$user->can('view.people') && !$user->hasRole('admin')) {
                throw new UserAuthorizationException('No tienes permiso para consultar personas.');
            }

            return true;
        });

        Gate::define('view.detail.people', function ($user) {
            if (!$user->can('show.people') && !$user->hasRole('admin')) {
                throw new UserAuthorizationException('No tienes permiso para consultar detalle de personas.');
            }

            return true;
        });

        Gate::define('view.all.locations', function ($user) {
            if (!$user->can('view.locations') && !$user->hasRole('admin')) {
                throw new UserAuthorizationException('No tienes permiso para consultar locaciones.');
            }

            return true;
        });

        Gate::define('view.detail.locations', function ($user) {
            if (!$user->can('show.locations') && !$user->hasRole('admin')) {
                throw new UserAuthorizationException('No tienes permiso para consultar detalle de locaciones.');
            }

            return true;
        });

        Gate::define('view.all.species', function ($user) {
            if (!$user->can('view.species') && !$user->hasRole('admin')) {
                throw new UserAuthorizationException('No tienes permiso para consultar especies.');
            }

            return true;
        });

        Gate::define('view.detail.species', function ($user) {
            if (!$user->can('show.species') && !$user->hasRole('admin')) {
                throw new UserAuthorizationException('No tienes permiso para consultar detalle de especies.');
            }

            return true;
        });
    }
}
