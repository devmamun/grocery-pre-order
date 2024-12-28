<?php

namespace Mamun\ShopPreOrder\Http\Controllers;

use Mamun\ShopPreOrder\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController
{
    public function index(): JsonResponse
    {
        $products = Product::all();
        return response()->json($products);
    }
}
