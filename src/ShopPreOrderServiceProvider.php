<?php

namespace Mamun\ShopPreOrder;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\ServiceProvider;
use Mamun\ShopPreOrder\Database\Seeders\ProductSeeder;
use Mamun\ShopPreOrder\Http\Middleware\RoleMiddleware;

class ShopPreOrderServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        // Register any application services.
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load the package routes
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Register middleware
        $this->app['router']->aliasMiddleware('role', RoleMiddleware::class);

        $this->callAfterResolving(DatabaseSeeder::class, function ($seeder) {
            $seeder->call(ProductSeeder::class);
        });
    }
}
