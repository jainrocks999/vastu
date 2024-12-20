<?php

namespace Botble\Ecommerce\Services;

use Botble\ACL\Models\User;
use Botble\Ecommerce\Enums\OrderHistoryActionEnum;
use Botble\Ecommerce\Events\OrderPaymentConfirmedEvent;
use Botble\Ecommerce\Models\Customer;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderHistory;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreatePaymentForOrderServiceApi
{
    public function execute(
        Order $order,
        string $paymentMethod,
        string $paymentStatus,
        string|int|null $customerId = null,
        ?string $chargeId = null,
    ): void {
        if (! is_plugin_active('payment')) {
            return;
        }
        if ($order->payment->exists) {
            $order->payment->update([
                'payment_channel' => $paymentMethod,
                'status' => $paymentStatus,
            ]);
        }

        /**
         * @var User $user
         */
        $user = $order['user_id'];
        $data = [
            'amount' => $order->amount,
            'currency' => cms_currency()->getDefaultCurrency()->title,
            'payment_channel' => $paymentMethod,
            'status' => $paymentStatus,
            'payment_type' => 'confirm',
            'order_id' => $order['id'],
            'charge_id' => $chargeId ?: Str::upper(Str::random(10)),
            'user_id' => $order['user_id'],
        ];

        if ($customerId) {
            $data = [
                ...$data,
                'customer_id' => $customerId,
                'customer_type' => Customer::class,
            ];
        }

        $payment = Payment::query()->create($data);
        $order->payment_id = $payment['id'];
        $order->save();

        if ($paymentStatus == PaymentStatusEnum::COMPLETED) {
            event(new OrderPaymentConfirmedEvent($order, $user));

            OrderHistory::query()->create([
                'action' => OrderHistoryActionEnum::CONFIRM_PAYMENT,
                'description' => trans('plugins/ecommerce::order.payment_was_confirmed_by', [
                    'money' => format_price($order->amount),
                ]),
                'order_id' => $order['id'],
                'user_id' => $order['user_id'],
            ]);
        }
    }
}
