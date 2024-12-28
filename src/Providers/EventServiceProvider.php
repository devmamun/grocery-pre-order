<?php

namespace Mamun\ShopPreOrder\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Mamun\ShopPreOrder\Events\PreOrderCreated;
use Mamun\ShopPreOrder\Listeners\SendPreOrderNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PreOrderCreated::class => [
            SendPreOrderNotification::class,
        ],
    ];

    public function boot()
    {
        parent::boot();

        $this->listen = [
            PreOrderCreated::class => [
                SendPreOrderNotification::class,
            ],
        ];
    }
}
