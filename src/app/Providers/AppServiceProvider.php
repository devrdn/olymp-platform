<?php

namespace App\Providers;

use App\Contracts\PermissionCheckerInterface;
use App\Services\PermissionChecker;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PermissionCheckerInterface::class, PermissionChecker::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::if('can', function (string $permission, $contestId = null): bool {
            return app(PermissionCheckerInterface::class)
                ->hasPermission(auth()->user(), $permission, $contestId);
        });
    }
}
