<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
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
        //
        Blade::if('hasAnyRole', function ($roles) {
            $user = auth()->user();

            if (!$user) {
                return false;
            }

            // Jika role adalah 'admin', periksa subrole
            if ($user->hasRole('admin')) {
                // Misalnya, jika subrole admin adalah 'superadmin'
                if ($user->subrole == 'superadmin') {
                    return true;
                }
            }

            // Jika bukan admin, periksa role biasa
            return $user->hasAnyRole($roles);
        });
    }
}
