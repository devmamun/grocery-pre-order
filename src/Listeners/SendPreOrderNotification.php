<?php

namespace Mamun\ShopPreOrder\Listeners;

use Mamun\ShopPreOrder\Events\PreOrderCreated;
use Mamun\ShopPreOrder\Jobs\AdminEmailJob;
use Mamun\ShopPreOrder\Jobs\UserEmailJob;
use Illuminate\Support\Facades\Bus;

class SendPreOrderNotification
{
    public function handle(PreOrderCreated $event)
    {
        // Chain the jobs to ensure the admin email is sent first
        Bus::chain([
            new AdminEmailJob($event->preOrder),
            new UserEmailJob($event->preOrder),
        ])->dispatch();
    }
}
