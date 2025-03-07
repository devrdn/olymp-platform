<?php

namespace App\Providers;

use App\Contracts\PermissionCheckerInterface;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerDirectives();
        $this->registerNamespaces();
    }


    /**
     * Registers custom Blade directives.
     *  
     */
    private function registerDirectives()
    {
        Blade::if('can', function (string $permission, $contestId = null): bool {
            return app(PermissionCheckerInterface::class)
                ->hasPermission(auth()->user(), $permission, $contestId);
        });
    }


    /**
     * Register the view namespaces for the application.
     *
     * @return void
     */
    private function registerNamespaces()
    {
        View::addNamespace('pages', resource_path('views/pages'));
    }
}
