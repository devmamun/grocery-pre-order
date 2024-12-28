<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mamun\ShopPreOrder\Models\PreOrder;
use Illuminate\support\Facades\Mail;
use Mamun\ShopPreOrder\Events\PreOrderCreated;
use Mamun\ShopPreOrder\Jobs\AdminEmailJob;
use Mamun\ShopPreOrder\Jobs\UserEmailJob;

class PreOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_pre_order_creation_sends_emails()
    {
        Mail::fake(); // Prevent actual email sending

        // Create a pre-order
        $preOrder = PreOrder::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'product_id' => 1,
            'phone' => '1234567890',
        ]);

        // Dispatch the event
        event(new PreOrderCreated($preOrder));

        // Assert that admin email was sent
        Mail::assertSent(AdminEmailJob::class);

        // Assert that user email was sent
        Mail::assertSent(UserEmailJob::class);
    }
}
