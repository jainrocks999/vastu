<?php

namespace Botble\Course\Services;

use Botble\Course\Enums\ShippingMethodEnum;
use Botble\Course\Facades\Cart;
use Botble\Course\Facades\CourseHelper;
use Botble\Course\Facades\OrderHelper;
use Botble\Course\Models\Order;
use Botble\Course\ValueObjects\CheckoutOrderData;
use Botble\Payment\Supports\PaymentHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class HandleCheckoutOrderData
{
    public function __construct(
        protected HandleApplyPromotionsService $applyPromotionsService,
        protected HandleShippingFeeService $shippingFeeService,
        protected HandleApplyCouponService $applyCouponService,
        protected HandleRemoveCouponService $removeCouponService,
        protected HandleSetCountryForPaymentCheckout $setCountryForPaymentCheckout
    ) {
    }

    public function execute(Request $request, Collection $courses, string $token, array &$sessionCheckoutData): CheckoutOrderData
    {
        $paymentMethod = null;

        if (is_plugin_active('payment')) {
            $paymentMethod = $request->input(
                'payment_method',
                session('selected_payment_method') ?: PaymentHelper::defaultPaymentMethod()
            );
        }

        if ($paymentMethod) {
            session()->put('selected_payment_method', $paymentMethod);
        }

        $this->setCountryForPaymentCheckout->execute($sessionCheckoutData);

        if (is_plugin_active('marketplace')) {
            [
                $sessionCheckoutData,
                $shipping,
                $defaultShippingMethod,
                $defaultShippingOption,
                $shippingAmount,
                $promotionDiscountAmount,
                $couponDiscountAmount,
            ] = apply_filters(PROCESS_CHECKOUT_ORDER_DATA_ECOMMERCE, $courses, $token, $sessionCheckoutData, $request);

            foreach ($sessionCheckoutData['marketplace'] as $storeData) {
                if (! empty($storeData['created_order_id'])) {
                    $order = Order::query()
                        ->where('id', $storeData['created_order_id'])
                        ->first();

                    if (
                        $order
                        && isset($storeData['shipping_amount'])
                        && $order->shipping_amount != $storeData['shipping_amount']
                    ) {
                        $order->update(['shipping_amount' => $storeData['shipping_amount']]);
                    }
                }
            }
        } else {
            $promotionDiscountAmount = $this->applyPromotionsService->execute($token);

            $sessionCheckoutData['promotion_discount_amount'] = $promotionDiscountAmount;

            $couponDiscountAmount = 0;
            if (session()->has('applied_coupon_code')) {
                $couponDiscountAmount = Arr::get($sessionCheckoutData, 'coupon_discount_amount', 0);
            }

            $orderTotal = Cart::instance('cart')->rawTotal() - $promotionDiscountAmount - $couponDiscountAmount;
            $orderTotal = max($orderTotal, 0);

            $shipping = [];

            $defaultShippingMethod = $request->input(
                'shipping_method',
                Arr::get($sessionCheckoutData, 'shipping_method', ShippingMethodEnum::DEFAULT)
            );

            $defaultShippingOption = $request->input(
                'shipping_option',
                Arr::get($sessionCheckoutData, 'shipping_option')
            );

            $defaultShippingOption = is_string($defaultShippingOption) ? $defaultShippingOption : null;

            $shippingAmount = 0;

            if ($isAvailableShipping = CourseHelper::isAvailableShipping($courses)) {
                $origin = CourseHelper::getOriginAddress();
                $shippingData = CourseHelper::getShippingData(
                    $courses,
                    $sessionCheckoutData,
                    $origin,
                    $orderTotal,
                    $paymentMethod,
                );

                $shipping = $this->shippingFeeService->execute($shippingData);

                foreach ($shipping as $key => &$shipItem) {
                    if (get_shipping_setting('free_ship', $key)) {
                        foreach ($shipItem as &$subShippingItem) {
                            Arr::set($subShippingItem, 'price', 0);
                        }
                    }
                }

                if ($shipping) {
                    if (! $defaultShippingMethod) {
                        $defaultShippingMethod = old(
                            'shipping_method',
                            Arr::get($sessionCheckoutData, 'shipping_method', Arr::first(array_keys($shipping)))
                        );
                    }

                    if (! $defaultShippingOption) {
                        $defaultShippingOption = old(
                            'shipping_option',
                            Arr::get($sessionCheckoutData, 'shipping_option', $defaultShippingOption)
                        );

                        if (! $defaultShippingOption) {
                            $defaultShippingOption = Arr::first(array_keys(Arr::first($shipping)));
                        }
                    }

                    $shippingAmount = Arr::get(
                        $shipping,
                        "$defaultShippingMethod.$defaultShippingOption.price",
                        0
                    );
                }

                Arr::set($sessionCheckoutData, 'shipping_method', $defaultShippingMethod);
                Arr::set($sessionCheckoutData, 'shipping_option', $defaultShippingOption);
                Arr::set($sessionCheckoutData, 'shipping_amount', $shippingAmount);

                if (! empty($sessionCheckoutData['created_order_id'])) {
                    $order = Order::query()
                        ->where('id', $sessionCheckoutData['created_order_id'])
                        ->first();

                    if ($order && $order->shipping_amount != $shippingAmount) {
                        $order->update(['shipping_amount' => $shippingAmount]);
                    }
                }

                OrderHelper::setOrderSessionData($token, $sessionCheckoutData);
            }

            if (session()->has('applied_coupon_code')) {
                if (! $request->input('applied_coupon')) {
                    $discount = $this->applyCouponService->getCouponData(
                        session('applied_coupon_code'),
                        $sessionCheckoutData
                    );
                    if (empty($discount)) {
                        $this->removeCouponService->execute();
                    } else {
                        $shippingAmount = Arr::get($sessionCheckoutData, 'is_free_shipping') ? 0 : $shippingAmount;
                    }
                } else {
                    $shippingAmount = Arr::get($sessionCheckoutData, 'is_free_shipping') ? 0 : $shippingAmount;
                }
            }

            $sessionCheckoutData['is_available_shipping'] = $isAvailableShipping;

            if (! $sessionCheckoutData['is_available_shipping']) {
                $shippingAmount = 0;
            }
        }

        $rawTotal = Cart::instance('cart')->rawTotal();
        $orderAmount = max($rawTotal - $promotionDiscountAmount - $couponDiscountAmount, 0);
        $orderAmount += (float) $shippingAmount;

        return new CheckoutOrderData(
            shipping: $shipping,
            sessionCheckoutData: $sessionCheckoutData,
            shippingAmount: $shippingAmount,
            rawTotal: $rawTotal,
            orderAmount: $orderAmount,
            promotionDiscountAmount: $promotionDiscountAmount,
            couponDiscountAmount: $couponDiscountAmount,
            defaultShippingMethod: $defaultShippingMethod,
            defaultShippingOption: $defaultShippingOption
        );
    }
}
