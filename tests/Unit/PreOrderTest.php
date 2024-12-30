<?php

namespace Mamun\ShopPreOrder\Test\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mamun\ShopPreOrder\Models\Product;
use Illuminate\Support\Facades\Auth;
use Mamun\ShopPreOrder\Models\PreOrder;
use Mockery;
use Illuminate\Foundation\Testing\WithFaker;

use Mamun\ShopPreOrder\Models\CustomUser;
class PreOrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private function mockAuthenticatedUser(array $attributes = [])
    {
        $defaultAttributes = [
            'id' => 1,
            'email' => 'test@example.com',
            'role' => 'admin',
        ];

        $userAttributes = array_merge($defaultAttributes, $attributes);

        $user = Mockery::mock(CustomUser::class);
        $user->shouldReceive('getAuthIdentifier')->andReturn($userAttributes['id']);
        $user->shouldReceive('getAttribute')->with('id')->andReturn($userAttributes['id']);
        $user->shouldReceive('getAttribute')->with('email')->andReturn($userAttributes['email']);
        $user->shouldReceive('getAttribute')->with('role')->andReturn($userAttributes['role']);
        $user->shouldReceive('can')->andReturn(true); // Simulate permissions

        Auth::shouldReceive('shouldUse')->with('api')->andReturnSelf();
        Auth::shouldReceive('guard')->with('api')->andReturnSelf();
        Auth::shouldReceive('user')->andReturn($user);
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('id')->andReturn($userAttributes['id']); // Mock the id() method

        return $user;
    }

    private function createPreOrder(array $overrides = [])
    {
        $product = Product::factory()->create();

        return PreOrder::create(array_merge([
            'product_id' => $product->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '1234567890'
        ], $overrides));
    }

    public function test_can_list_pre_orders()
    {
        $this->mockAuthenticatedUser();

        $preOrder = $this->createPreOrder();

        $response = $this->getJson('/api/pre-orders');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'status', 'message'])
            ->assertJsonFragment(['name' => $preOrder->name]);

        Mockery::close();
    }

    public function test_can_create_pre_order()
    {
        $this->mockAuthenticatedUser();

        $product = Product::factory()->create();

        $response = $this->postJson('/api/pre-orders', [
            'product_id' => $product->id,
            'name' => 'New User',
            'email' => 'newuser@example.com',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['name' => 'New User']);
    }

    public function test_can_show_pre_order()
    {
        $this->mockAuthenticatedUser();

        $preOrder = $this->createPreOrder();

        $response = $this->getJson("/api/pre-orders/{$preOrder->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => $preOrder->name]);
    }

    public function test_can_update_pre_order()
    {
        $this->mockAuthenticatedUser();

        $preOrder = $this->createPreOrder();

        $response = $this->putJson("/api/pre-orders/{$preOrder->id}", [
            'name' => 'Updated Name',
            'email' => 'test@example.com',
            'product_id' => $preOrder->product_id
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Name']);
    }

    public function test_can_delete_pre_order()
    {
        $this->mockAuthenticatedUser();
        Auth::shouldReceive('id')->andReturn(1);

        $preOrder = $this->createPreOrder();

        $response = $this->deleteJson("/api/pre-orders/{$preOrder->id}");

        $response->assertStatus(200);
    }

    public function test_validation_error_on_create()
    {
        $this->mockAuthenticatedUser();

        $response = $this->postJson('/api/pre-orders', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['product_id', 'name', 'email']);
    }

    public function test_rate_limiting()
    {
        $product = Product::factory()->create();

        for ($i = 0; $i < config('grocery.rate_limit_per_minute'); $i++) {
            $this->postJson('/api/pre-orders', [
                'product_id' => $product->id,
                'name' => 'Rate Test',
                'email' => 'rate@example.com',
            ]);
        }

        $response = $this->postJson('/api/pre-orders', [
            'product_id' => $product->id,
            'name' => 'Exceed Test',
            'email' => 'exceed@example.com',
        ]);

        $response->assertStatus(429);
    }

    public function test_manager_can_see_list_pre_orders()
    {
        $this->mockAuthenticatedUser(['role' => 'manager']);

        $this->createPreOrder();

        $this->getJson('/api/pre-orders')->assertStatus(200);
    }

    public function test_manager_can_not_delete_pre_orders()
    {
        $this->mockAuthenticatedUser(['role' => 'manager']);

        $preOrder = $this->createPreOrder();

        $this->deleteJson("/api/pre-orders/{$preOrder->id}")->assertStatus(403);
    }

    public function test_manager_can_not_update_pre_orders()
    {
        $this->mockAuthenticatedUser(['role' => 'manager']);

        $preOrder = $this->createPreOrder();

        $this->putJson("/api/pre-orders/{$preOrder->id}", [
            'name' => 'Updated Name',
            'email' => 'test@example.com',
            'product_id' => $preOrder->product_id
        ])->assertStatus(403);
    }
}
