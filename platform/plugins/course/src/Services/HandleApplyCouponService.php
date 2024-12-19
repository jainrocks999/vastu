<?php

namespace Botble\Course\Services;

use Botble\Course\Enums\DiscountTargetEnum;
use Botble\Course\Enums\DiscountTypeEnum;
use Botble\Course\Enums\DiscountTypeOptionEnum;
use Botble\Course\Facades\Cart;
use Botble\Course\Facades\OrderHelper;
use Botble\Course\Models\CourseDiscount;
use Botble\Course\Models\Course;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class HandleApplyCouponService
{
    // public function execute(string $coupon, array $sessionData = [], array $cartData = [], ?string $prefix = ''): array
    // {
    //     $token = OrderHelper::getOrderSessionToken();

    //     if (! $token) {
    //         $token = OrderHelper::getOrderSessionToken();
    //     }

    //     if (! $sessionData) {
    //         $sessionData = OrderHelper::getOrderSessionData($token);
    //     }

    //     $rawTotal = Arr::get($cartData, 'rawTotal', Cart::instance('cart')->rawTotal());

    //     $sessionData['raw_total'] = $rawTotal;

    //     $couponCode = trim($coupon);

    //     $discount = $this->getCouponData($couponCode, $sessionData);

    //     if (! $discount) {
    //         return [
    //             'error' => true,
    //             'message' => trans('plugins/course::discount.invalid_coupon'),
    //         ];
    //     }

    //     $customerId = auth('customer')->check() ? auth('customer')->id() : 0;

    //     $resultCondition = $this->checkConditionDiscount($discount, $sessionData, $customerId);
    //     if (Arr::get($resultCondition, 'error')) {
    //         return $resultCondition;
    //     }

    //     $couponDiscountAmount = 0;
    //     $isFreeShipping = false;
    //     $discountTypeOption = null;
    //     $validCartItemIds = [];

    //     if ($discount->type_option === DiscountTypeOptionEnum::SHIPPING) {
    //         $isFreeShipping = true;
    //     } else {
    //         $discountTypeOption = $discount->type_option;
    //         $couponData = $this->getCouponDiscountAmount($discount, $cartData);

    //         $couponDiscountAmount = Arr::get($couponData, 'discount_amount', 0);
    //         $validCartItemIds = Arr::get($couponData, 'valid_cart_item_ids', 0);
    //     }

    //     if ($couponDiscountAmount < 0) {
    //         $couponDiscountAmount = 0;
    //     }

    //     if ($isFreeShipping) {
    //         if ($prefix) {
    //             Arr::set($sessionData, $prefix . 'is_free_shipping', true);
    //         } else {
    //             Arr::set($sessionData, 'is_free_shipping', true);
    //         }
    //     }

    //     if ($prefix) {
    //         switch ($discountTypeOption) {
    //             case DiscountTypeOptionEnum::PERCENTAGE:
    //             case DiscountTypeOptionEnum::SAME_PRICE:
    //                 Arr::set($sessionData, $prefix . 'coupon_discount_amount', $couponDiscountAmount);

    //                 break;
    //             default:
    //                 Arr::set($sessionData, $prefix . 'coupon_discount_amount', 0);

    //                 break;
    //         }
    //     } else {
    //         Arr::set($sessionData, 'coupon_discount_amount', $couponDiscountAmount);
    //     }

    //     OrderHelper::setOrderSessionData($token, $sessionData);

    //     session()->put('applied_coupon_code', $couponCode);
    //     session()->forget('auto_apply_coupon_code');

    //     return [
    //         'error' => false,
    //         'data' => [
    //             'discount_amount' => $couponDiscountAmount,
    //             'is_free_shipping' => $isFreeShipping,
    //             'discount_type_option' => $discount->type_option,
    //             'discount' => $discount,
    //             'valid_cart_item_ids' => $validCartItemIds,
    //         ],
    //     ];
    // }

    public function getCouponData(string $couponCode, array $sessionData = []): CourseDiscount|Model|null
    {
        $couponCode = trim($couponCode);

        // @phpstan-ignore-next-line
        return CourseDiscount::query()
            ->where('code', $couponCode)
            ->where('type', DiscountTypeEnum::COUPON)
            ->where('start_date', '<=', Carbon::now())
            ->where(function (Builder $query) use ($sessionData) {
                $query
                    ->where(function (Builder $sub) {
                        return $sub
                            ->whereIn('type_option', [DiscountTypeOptionEnum::AMOUNT, DiscountTypeOptionEnum::PERCENTAGE])
                            ->where(function (Builder $subSub) {
                                return $subSub
                                    ->whereNull('end_date')
                                    ->orWhere('end_date', '>=', Carbon::now());
                            });
                    })
                    ->orWhere(function (Builder $sub) {
                        return $sub
                            ->where('type_option', DiscountTypeOptionEnum::SAME_PRICE)
                            ->whereIn('target', [DiscountTargetEnum::COURSE_CATEGORIES]);
                    });
            })
            ->available()
            ->first();
    }

    public function applyCouponWhenCreatingOrderFromStudent(Request $request,$cartData)
    {
        
        $couponCode = trim($request->input('coupon_code'));
        $param = [];
        $param['price'] = $cartData['price'];
        $param['start_date'] = $cartData;
        $param['end_date'] = $cartData;
        $param['course_category_id'] = $cartData;
        
        $courseDiscount = $this->getCouponData($couponCode, $param);
        
        if (! $courseDiscount) {
            return ['error'=>true,'status'=>400,'message' => trans('plugins/course::discount.invalid_coupon')];
        }
        
        $customerId = $request->input('user_id');
        $resultCondition = $this->checkConditionDiscount($courseDiscount, $param, $customerId);
        if (Arr::get($resultCondition, 'error')) {
            return $resultCondition;
        }

        $couponDiscountAmount = 0;
        $isFreeShipping = false;

        if ($courseDiscount->type_option === DiscountTypeOptionEnum::SHIPPING) {
            $isFreeShipping = true;
        } else {
            $couponData = $this->getCouponDiscountAmount($courseDiscount, $cartData);
            $couponDiscountAmount = Arr::get($couponData, 'discount_amount', 0);
        }
        if ($couponDiscountAmount < 0) {
            $couponDiscountAmount = 0;
        }

        return [
            'error' => false,
            'data' => [
                'discount_amount' => $couponDiscountAmount,
                'is_free_shipping' => $isFreeShipping
            ],
        ];
    }

    public function checkConditionDiscount(CourseDiscount|Model $discount, array $sessionData = [], ?int $customerId = 0): array
    {

        if ($discount->target === DiscountTargetEnum::ONCE_PER_CUSTOMER) {
            if (! $customerId) {
                return ['error'=>true, 'message' =>  trans('plugins/course::discount.you_need_login_to_use_coupon_code')];
            } elseif ($discount->usedByCustomers()->where('customer_id', $customerId)->exists()) {
                return ['error'=>true, 'message' =>  trans('plugins/course::discount.you_used_coupon_code')];
            }
        }

        $rawTotal = $sessionData['price'];
        
        if (
            in_array($discount->type_option, [DiscountTypeOptionEnum::AMOUNT, DiscountTypeOptionEnum::PERCENTAGE])
            && $discount->target == DiscountTargetEnum::MINIMUM_ORDER_AMOUNT && $discount->min_order_price > $rawTotal
        ) {
            return [
                'error'=>true,
                'message' => trans('plugins/course::discount.minimum_order_amount_error', [
                    'minimum_amount' => format_price($discount->min_order_price),
                    'add_more' => format_price($rawTotal - $discount->min_order_price),
                ]),
            ];
        }

        return [
            'error' => false,
        ];
    }

    protected function getCouponDiscountAmount(CourseDiscount|Model $discount, $cartData)
    {
        $couponDiscountAmount = 0;
        $discountValue = max($discount->value, 0);
        $rawTotal = Arr::get($cartData, 'price');
        $cartItems = 1;
        $countCart = 1;
        $courses = $cartData;

        if (! $courses instanceof Collection) {
            $courses = new Collection($courses);
        }

        $validCartItems = collect();
        switch ($discount->type_option) {
            case DiscountTypeOptionEnum::AMOUNT:
                switch ($discount->target) {
                    case DiscountTargetEnum::MINIMUM_ORDER_AMOUNT:
                    case DiscountTargetEnum::ONCE_PER_CUSTOMER:
                    case DiscountTargetEnum::ALL_ORDERS:
                        $couponDiscountAmount += min($discountValue, $rawTotal);
                        break;
                    case DiscountTargetEnum::COURSE_CATEGORIES:
                        $courses->loadMissing([
                            'variationInfo',
                            'categories',
                            'variationInfo.configurableCourse',
                            'variationInfo.configurableCourse.categories',
                        ]);

                        $discountCourseCategories = $discount
                            ->courseCategories()
                            ->pluck('course_category.id')
                            ->all();

                        $validCartItems = $cartItems->filter(function ($cartItem) use ($courses, $discountCourseCategories) {
                            /**
                             * @var Course $course
                             */
                            $course = $courses->filter(function ($item) use ($cartItem) {
                                return $item->id == $cartItem->id || $item->original_course->id == $cartItem->id;
                            })->first();
                            if (! $course) {
                                return false;
                            }

                            $courseCategories = $course->original_course->categories->pluck('id')->all();

                            if (! empty(array_intersect($courseCategories, $discountCourseCategories))) {
                                return true;
                            }

                            return false;
                        });

                        if ($discount->discount_on === 'per-order') {
                            $validRawTotal = Cart::instance('cart')->rawTotalByItems($validCartItems);
                            $couponDiscountAmount += min($discountValue, $validRawTotal);
                        } elseif ($discount->discount_on === 'per-every-item') {
                            foreach ($validCartItems as $cartItem) {
                                $couponDiscountAmount += min($discountValue * $cartItem->qty, $cartItem->total);
                            }
                        }

                        break;

                    default:
                        if ($countCart >= $discount->course_quantity) {
                            $couponDiscountAmount += min($discountValue, $rawTotal);
                        }
                        break;
                }
                break;
            case DiscountTypeOptionEnum::PERCENTAGE:
                switch ($discount->target) {
                    case DiscountTargetEnum::MINIMUM_ORDER_AMOUNT:
                    case DiscountTargetEnum::ONCE_PER_CUSTOMER:
                    case DiscountTargetEnum::ALL_ORDERS:
                        $couponDiscountAmount = $rawTotal * $discountValue / 100;

                        break;
                    case DiscountTargetEnum::COURSE_CATEGORIES:
                        $courses->loadMissing([
                            'variationInfo',
                            'categories',
                            'variationInfo.configurableCourse',
                            'variationInfo.configurableCourse.categories',
                        ]);

                        $discountCourseCategories = $discount
                            ->courseCategories()
                            ->pluck('ec_course_categories.id')
                            ->all();

                        $validCartItems = $cartItems->filter(function ($cartItem) use ($courses, $discountCourseCategories) {
                            /**
                             * @var Course $course
                             */
                            $course = $courses->filter(function ($item) use ($cartItem) {
                                return $item->id == $cartItem->id || $item->original_course->id == $cartItem->id;
                            })->first();
                            if (! $course) {
                                return false;
                            }

                            $courseCategories = $course->original_course->categories->pluck('id')->all();

                            if (! empty(array_intersect($courseCategories, $discountCourseCategories))) {
                                return true;
                            }

                            return false;
                        });

                        foreach ($validCartItems as $cartItem) {
                            $couponDiscountAmount += $cartItem->total * $discountValue / 100;
                        }

                        break;

                    default:
                        if ($countCart >= $discount->course_quantity) {
                            $couponDiscountAmount = $rawTotal * $discountValue / 100;
                        }

                        break;
                }

                break;
            case DiscountTypeOptionEnum::SAME_PRICE:
                if (in_array($discount->target, [DiscountTargetEnum::SPECIFIC_PRODUCT, DiscountTargetEnum::PRODUCT_VARIANT])) {
                    foreach ($cartItems as $cartItem) {
                        if (in_array($cartItem->id, $discount->courses->pluck('id')->all())) {
                            $couponDiscountAmount = max($cartItem->priceTax - $discountValue, 0) * $cartItem->qty;
                        }
                    }
               } elseif ($discount->target === DiscountTargetEnum::PRODUCT_CATEGORIES) {
                    $courses->loadMissing([
                        'variationInfo',
                        'categories',
                        'variationInfo.configurableCourse',
                        'variationInfo.configurableCourse.categories',
                    ]);

                    $discountCourseCategories = $discount
                        ->courseCategories()
                        ->pluck('ec_course_categories.id')
                        ->all();

                    $validCartItems = $cartItems->filter(function ($cartItem) use ($courses, $discountCourseCategories) {
                        /**
                         * @var Course $course
                         */
                        $course = $courses->filter(function ($item) use ($cartItem) {
                            return $item->id == $cartItem->id || $item->original_course->id == $cartItem->id;
                        })->first();
                        if (! $course) {
                            return false;
                        }

                        $courseCategories = $course->original_course->categories->pluck('id')->all();

                        if (! empty(array_intersect($courseCategories, $discountCourseCategories))) {
                            return true;
                        }

                        return false;
                    });

                    foreach ($validCartItems as $cartItem) {
                        $couponDiscountAmount += max($cartItem->total - $discountValue, 0) * $cartItem->qty;
                    }
                }

                break;
        }

        return [
            'discount_amount' => $couponDiscountAmount,
            'valid_cart_item_ids' => $validCartItems->pluck('id'),
        ];
    }
}
