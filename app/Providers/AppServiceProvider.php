<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

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
//        Gate::define('user', function ($user) {
//            return true;// استبدل هذا الشرط بشرط يناسب مشروعك
//        });
        Gate::before(function ($user, $ability) {
            if ($user->email=="super@admin.com") {
                return true;
            }
        });
    }
}
