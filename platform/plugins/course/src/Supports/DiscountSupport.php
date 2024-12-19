<?php

namespace Botble\Course\Supports;

use Botble\Course\Enums\DiscountTargetEnum;
use Botble\Course\Enums\DiscountTypeEnum;
use Botble\Course\Models\Discount;
use Botble\Course\Repositories\Interfaces\DiscountInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DiscountSupport
{
    protected Collection|array $promotions = [];

    public int|string $customerId = 0;

    public function __construct()
    {
        if (! is_in_admin() && auth('customer')->check()) {
            $this->setCustomerId(auth('customer')->id());
        }
    }

    public function setCustomerId(int|string $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getCustomerId(): int|string
    {
        return $this->customerId;
    }

    public function promotionForCourse(array $courseIds): ?Discount
    {
        if (! $this->promotions) {
            $this->getAvailablePromotions();
        }

        foreach ($this->promotions as $promotion) {
            switch ($promotion->target) {
                case DiscountTargetEnum::SPECIFIC_PRODUCT:
                case DiscountTargetEnum::PRODUCT_VARIANT:
                    foreach ($promotion->courses as $course) {
                        if (in_array($course->id, $courseIds)) {
                            return $promotion;
                        }
                    }

                    break;

                case DiscountTargetEnum::PRODUCT_COLLECTIONS:
                    $courseCollectionIds = DB::table('ec_course_collection_courses')
                        ->whereIn('course_id', $courseIds)
                        ->pluck('course_collection_id')
                        ->all();

                    foreach ($promotion->courseCollections as $courseCollection) {
                        if (in_array($courseCollection->id, $courseCollectionIds)) {
                            return $promotion;
                        }
                    }

                    break;

                case DiscountTargetEnum::CUSTOMER:
                    if ($this->customerId) {
                        foreach ($promotion->customers as $customer) {
                            if ($customer->id == $this->customerId) {
                                return $promotion;
                            }
                        }
                    }

                    break;

                case DiscountTargetEnum::PRODUCT_CATEGORIES:
                    $courseCategoriesIds = DB::table('ec_course_category_course')
                        ->whereIn('course_id', $courseIds)
                        ->pluck('category_id')
                        ->all();

                    foreach ($promotion->courseCategories as $courseCategories) {
                        if (in_array($courseCategories->id, $courseCategoriesIds)) {
                            return $promotion;
                        }
                    }

                    break;

                case DiscountTargetEnum::ALL_ORDERS:
                    if ($promotion->course_quantity == 1) {
                        return $promotion;
                    }

                    break;
            }
        }

        return null;
    }

    public function getAvailablePromotions(bool $forCourseSingle = true): Collection
    {
        if (! $this->promotions instanceof Collection) {
            $this->promotions = collect();
        }

        if ($this->promotions->isEmpty()) {
            $this->promotions = app(DiscountInterface::class)
                ->getAvailablePromotions(['courses', 'customers', 'courseCollections', 'courseCategories'], $forCourseSingle);
        }

        return $this->promotions;
    }

    public function afterOrderPlaced(string $couponCode, int|string|null $customerId = 0): void
    {
        $now = Carbon::now();

        /**
         * @var Discount $discount
         */
        $discount = Discount::query()
            ->where('code', $couponCode)
            ->where('type', DiscountTypeEnum::COUPON)
            ->where('start_date', '<=', $now)
            ->where(function (Builder $query) use ($now) {
                $query
                    ->whereNull('end_date')
                    ->orWhere('end_date', '>', $now);
            })
            ->first();

        if ($discount) {
            $discount->total_used++;
            $discount->save();

            if (func_num_args() == 1) {
                $customerId = auth('customer')->check() ? auth('customer')->id() : 0;
            }

            if ($discount->target === DiscountTargetEnum::ONCE_PER_CUSTOMER && $customerId) {
                $discount->usedByCustomers()->syncWithoutDetaching([$customerId]);
            }
        }
    }

    public function afterOrderCancelled(string $couponCode, int|string|null $customerId = 0): void
    {
        /**
         * @var Discount $discount
         */
        $discount = Discount::query()
            ->where('code', $couponCode)
            ->where('type', DiscountTypeEnum::COUPON)
            ->first();

        if ($discount) {
            $discount->total_used--;
            $discount->save();

            if (func_num_args() == 1) {
                $customerId = auth('customer')->check() ? auth('customer')->id() : 0;
            }

            if ($discount->target === DiscountTargetEnum::ONCE_PER_CUSTOMER && $customerId) {
                $discount->usedByCustomers()->detach($customerId);
            }
        }
    }
}
