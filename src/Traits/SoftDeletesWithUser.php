<?php

namespace Mamun\ShopPreOrder\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

trait SoftDeletesWithUser
{
    use SoftDeletes;

    /**
     * Boot the soft delete trait for the model.
     *
     * @return void
     */
    public static function bootSoftDeletesWithUser()
    {
        static::deleting(function (Model $model) {
            // Set the deleted_by_id to the current user's ID
            $model->deleted_by_id = Auth::id();
            $model->save(); // Save the model to update the deleted_by_id
        });
    }
}
