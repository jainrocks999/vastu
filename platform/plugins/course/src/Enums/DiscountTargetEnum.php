<?php

namespace Botble\Course\Enums;

use Botble\Base\Supports\Enum;

/**
 * @method static DiscountTargetEnum ALL_ORDERS()
 * @method static DiscountTargetEnum CUSTOMER()
 * @method static DiscountTargetEnum MINIMUM_ORDER_AMOUNT()
 * @method static DiscountTargetEnum ONCE_PER_CUSTOMER()
 * @method static DiscountTargetEnum PRODUCT_VARIANT()
 * @method static DiscountTargetEnum PRODUCT_COLLECTIONS()
 * @method static DiscountTargetEnum SPECIFIC_PRODUCT()
 */
class DiscountTargetEnum extends Enum
{
    public const ALL_ORDERS = 'all-orders';
    public const CUSTOMER = 'customer';
    public const MINIMUM_ORDER_AMOUNT = 'amount-minimum-order';
    public const ONCE_PER_CUSTOMER = 'once-per-customer';
    public const COURSE_VARIANT = 'course-variant';
    public const COURSE_COLLECTIONS = 'group-courses';
    public const SPECIFIC_COURSE = 'specific-course';
    public const COURSE_CATEGORIES = 'courses-by-category';

    public static $langPath = 'plugins/course::discount.enums.targets';
}
