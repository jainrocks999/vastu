<?php

namespace Botble\Course\Traits;

use Botble\Course\Enums\OrderStatusEnum;
use Botble\Course\Facades\CourseHelper;
use Botble\Course\Models\Order;
use Botble\Course\Models\Review;

trait CheckReviewConditionTrait
{
    protected function checkReviewCondition(int|string $courseId): array
    {
        if (! auth('customer')->check()) {
            return [
                'error' => false,
            ];
        }

        $customerId = auth('customer')->id();

        $exists = Review::query()
            ->where([
                'customer_id' => $customerId,
                'course_id' => $courseId,
            ])
            ->exists();

        if ($exists) {
            return [
                'error' => true,
                'message' => __('You have reviewed this course already!'),
            ];
        }

        if (CourseHelper::onlyAllowCustomersPurchasedToReview()) {
            $order = Order::query()
                ->where([
                    'user_id' => $customerId,
                    'status' => OrderStatusEnum::COMPLETED,
                ])
                ->join('ec_order_course', function ($query) use ($courseId) {
                    $query
                        ->on('ec_order_course.order_id', 'ec_orders.id')
                        ->leftJoin('ec_course_variations', 'ec_course_variations.course_id', 'ec_order_course.course_id')
                        ->where(function ($query) use ($courseId) {
                            $query->where('ec_course_variations.configurable_course_id', $courseId)
                            ->orWhere('ec_order_course.course_id', $courseId);
                        });
                })
                ->exists();

            if (! $order) {
                return [
                    'error' => true,
                    'message' => __('Please purchase the course for a review!'),
                ];
            }
        }

        return [
            'error' => false,
        ];
    }
}
