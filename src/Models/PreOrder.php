<?php

namespace Mamun\ShopPreOrder\Models;

use Illuminate\Database\Eloquent\Model;
use Mamun\ShopPreOrder\Traits\SoftDeletesWithUser;

class PreOrder extends Model
{
    use SoftDeletesWithUser;

    protected $fillable = ['name', 'email', 'phone', 'product_id', 'deleted_by_id'];

    /**
     * Get the product associated with the pre-order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
