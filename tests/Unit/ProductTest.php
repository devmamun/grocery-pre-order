<?php

namespace Mamun\ShopPreOrder\Test\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mamun\ShopPreOrder\Models\Product; // Import the Product model
use Illuminate\Foundation\Testing\WithFaker;

class ProductTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_get_products()
    {
        Product::factory()->count(5)->create();
        // Act: Send a GET request to the /api/products endpoint
        $response = $this->getJson('/api/products');

        // Assert: Check the response status and structure
        $response->assertStatus(200)
                 ->assertJsonCount(5); // Ensure it returns 3 products
    }
}
