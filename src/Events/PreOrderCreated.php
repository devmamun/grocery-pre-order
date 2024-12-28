<?php

namespace Mamun\ShopPreOrder\Events;

use Mamun\ShopPreOrder\Models\PreOrder;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PreOrderCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public PreOrder $preOrder;

    public function __construct(PreOrder $preOrder)
    {
        $this->preOrder = $preOrder;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
