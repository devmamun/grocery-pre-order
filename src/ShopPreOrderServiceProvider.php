<?php

namespace Mamun\ShopPreOrder;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\ServiceProvider;
use Mamun\ShopPreOrder\Database\Seeders\ProductSeeder;
use Mamun\ShopPreOrder\Http\Middleware\RateLimitMiddleware;
use Mamun\ShopPreOrder\Http\Middleware\RoleMiddleware;
use Mamun\ShopPreOrder\Providers\EventServiceProvider;

class ShopPreOrderServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        // Load and merge the package configuration file
        $configPath = __DIR__ . '/../config/grocery.php';
        $this->mergeConfigFrom($configPath, 'grocery');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(EventServiceProvider::class);

        // Load the package routes
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'grocery');

        // Register middleware
        $this->app['router']->aliasMiddleware('role', RoleMiddleware::class);
        $this->app['router']->aliasMiddleware('ratelimit', RateLimitMiddleware::class);

        $this->callAfterResolving(DatabaseSeeder::class, function ($seeder) {
            $seeder->call(ProductSeeder::class);
        });

        // Publish configuration
        $this->publishes([
            __DIR__ . '/../config/grocery.php' => config_path('grocery.php'),
        ], 'config');
    }
}
