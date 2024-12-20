<?php

namespace Botble\Ecommerce\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
class CartTemp extends BaseModel
{

    protected $table = 'ec_cart_temp';

    protected $fillable = [
        'id',
        'user_id',
        'rowid',
        'product_id',
        'qty',
        'name',
        'price',
        'option',
        'taxrate',
        'subtotal',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
