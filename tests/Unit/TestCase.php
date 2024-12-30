<?php
namespace Mamun\ShopPreOrder\Test\Unit;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Mamun\ShopPreOrder\ShopPreOrderServiceProvider;

use function Orchestra\Testbench\artisan;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            ShopPreOrderServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup database
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

    }
}
