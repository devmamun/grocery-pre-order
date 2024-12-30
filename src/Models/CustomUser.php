<?php

namespace Mamun\ShopPreOrder\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class CustomUser extends Model implements Authenticatable
{
    // Implement all necessary methods from Authenticatable, or extend the Authenticatable trait
    use \Illuminate\Auth\Authenticatable;

    // Custom user model logic
}


