<?php

namespace Botble\Course\Facades;

use Botble\Course\Supports\DiscountSupport;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Botble\Course\Supports\DiscountSupport setCustomerId(string|int $customerId)
 * @method static string|int getCustomerId()
 * @method static \Botble\Course\Models\Discount|null promotionForCourse(array $courseIds)
 * @method static \Illuminate\Support\Collection getAvailablePromotions(bool $forCourseSingle = true)
 * @method static void afterOrderPlaced(string $couponCode, string|int|null $customerId = 0)
 * @method static void afterOrderCancelled(string $couponCode, string|int|null $customerId = 0)
 *
 * @see \Botble\Course\Supports\DiscountSupport
 */
class Discount extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DiscountSupport::class;
    }
}
