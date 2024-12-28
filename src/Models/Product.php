<?php

namespace Mamun\ShopPreOrder\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mamun\ShopPreOrder\Database\Factories\ProductFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price'];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return ProductFactory::new();
    }
}
