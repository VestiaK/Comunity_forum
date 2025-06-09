<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Components\Layout;
use App\View\Components\Navbar;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use Illuminate\Support\Facades\Gate;

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
        Blade::component('app-layout', Layout::class);
        Blade::component('navigation-layout', Navbar::class);

        View::composer('*', function ($view) {
            $view->with('categories', Category::all());
        });

        Gate::define('moderate', function ($user) {
            return $user->isModerator();
        });

        Gate::before(function ($user, $ability) {
            if ($user->role === 'admin') {
                return true;
            }
        });
    }
}
