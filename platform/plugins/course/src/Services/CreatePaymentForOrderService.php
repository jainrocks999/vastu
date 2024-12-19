<?php

namespace Botble\Course\Services;

use Botble\ACL\Models\User;
use Botble\Course\Enums\OrderHistoryActionEnum;
use Botble\Course\Events\OrderPaymentConfirmedEvent;
use Botble\Course\Models\Customer;
use Botble\Course\Models\Order;
use Botble\Course\Models\OrderHistory;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreatePaymentForOrderService
{
    public function execute(
        Order $order,
        string $paymentMethod,
        string $paymentStatus = PaymentStatusEnum::PENDING,
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
        $user = Auth::user();

        $data = [
            'amount' => $order->amount,
            'currency' => cms_currency()->getDefaultCurrency()->title,
            'payment_channel' => $paymentMethod,
            'status' => $paymentStatus,
            'payment_type' => 'confirm',
            'order_id' => $order->getKey(),
            'charge_id' => $chargeId ?: Str::upper(Str::random(10)),
            'user_id' => $user->getKey(),
        ];

        if ($customerId) {
            $data = [
                ...$data,
                'customer_id' => $customerId,
                'customer_type' => Customer::class,
            ];
        }

        $payment = Payment::query()->create($data);

        $order->payment_id = $payment->getKey();
        $order->save();

        if ($paymentStatus == PaymentStatusEnum::COMPLETED) {
            event(new OrderPaymentConfirmedEvent($order, $user));

            OrderHistory::query()->create([
                'action' => OrderHistoryActionEnum::CONFIRM_PAYMENT,
                'description' => trans('plugins/course::order.payment_was_confirmed_by', [
                    'money' => format_price($order->amount),
                ]),
                'order_id' => $order->getKey(),
                'user_id' => $user->getKey(),
            ]);
        }
    }
}
