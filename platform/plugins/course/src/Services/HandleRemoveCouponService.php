<?php

namespace Botble\Course\Services;

use Botble\Course\Enums\DiscountTypeEnum;
use Botble\Course\Enums\DiscountTypeOptionEnum;
use Botble\Course\Facades\OrderHelper;
use Botble\Course\Models\Discount;
use Illuminate\Support\Arr;

class HandleRemoveCouponService
{
    public function execute(?string $prefix = '', bool $isForget = true): array
    {
        if (! session()->has('applied_coupon_code')) {
            return [
                'error' => true,
                'message' => trans('plugins/course::discount.not_used'),
            ];
        }

        $couponCode = session('applied_coupon_code');

        $discount = Discount::query()
            ->where('code', $couponCode)
            ->where('type', DiscountTypeEnum::COUPON)
            ->first();

        $token = OrderHelper::getOrderSessionToken();

        $sessionData = OrderHelper::getOrderSessionData($token);

        if ($discount && $discount->type_option === DiscountTypeOptionEnum::SHIPPING) {
            Arr::set($sessionData, $prefix . 'is_free_shipping', false);
        }

        Arr::set($sessionData, $prefix . 'coupon_discount_amount', 0);
        OrderHelper::setOrderSessionData($token, $sessionData);

        if ($isForget) {
            session()->forget('applied_coupon_code');
        }

        if (session()->has('auto_apply_coupon_code') && session('auto_apply_coupon_code') === $couponCode) {
            session()->forget('auto_apply_coupon_code');
        }

        return [
            'error' => false,
        ];
    }
}
