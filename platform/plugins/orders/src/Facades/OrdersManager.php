<?php

namespace Botble\Orders\Facades;

use Botble\Orders\Supports\OrdersManager as OrdersManagerSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string display(string $location, array $attributes = [])
 * @method static \Botble\Orders\Supports\OrdersManager load(bool $force = false, array $with = [])
 * @method static bool locationHasOrders(string $location)
 * @method static string|null displayOrders(string|null $key, array $attributes = [])
 * @method static \Illuminate\Support\Collection getData(bool $isLoad = false, bool $isNotExpired = false)
 * @method static \Botble\Orders\Supports\OrdersManager registerLocation(string $key, string $name)
 * @method static array getLocations()
 * @method static \Botble\Orders\Models\Orders|null getOrders(string $key)
 *
 * @see \Botble\Orders\Supports\OrdersManager
 */
class OrdersManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OrdersManagerSupport::class;
    }
}
