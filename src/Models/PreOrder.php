<?php

namespace Mamun\ShopPreOrder\Models;

use Illuminate\Database\Eloquent\Model;
use Mamun\ShopPreOrder\Traits\SoftDeletesWithUser;

class PreOrder extends Model
{
    use SoftDeletesWithUser;

    protected $fillable = ['name', 'email', 'phone', 'product_id', 'deleted_by_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
