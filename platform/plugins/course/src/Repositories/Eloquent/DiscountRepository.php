<?php

namespace Botble\Course\Repositories\Eloquent;

use Botble\Course\Enums\DiscountTargetEnum;
use Botble\Course\Enums\DiscountTypeEnum;
use Botble\Course\Repositories\Interfaces\DiscountInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class DiscountRepository extends RepositoriesAbstract implements DiscountInterface
{
    public function getAvailablePromotions(array $with = [], bool $forCourseSingle = false)
    {
        $data = $this->model
            ->where('type', DiscountTypeEnum::PROMOTION)
            ->where('start_date', '<=', Carbon::now())
            ->where(function (EloquentBuilder $query) {
                return $query
                    ->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::now());
            })
            ->where(function (EloquentBuilder $query) use ($forCourseSingle) {
                return $query
                    ->whereIn('target', [DiscountTargetEnum::ALL_ORDERS, DiscountTargetEnum::MINIMUM_ORDER_AMOUNT])
                    ->orWhere(function (EloquentBuilder $sub) use ($forCourseSingle) {
                        $compare = '>';

                        if ($forCourseSingle) {
                            $compare = '=';
                        }

                        return $sub
                            ->whereIn('target', [
                                DiscountTargetEnum::CUSTOMER,
                                DiscountTargetEnum::PRODUCT_COLLECTIONS,
                                DiscountTargetEnum::PRODUCT_CATEGORIES,
                                DiscountTargetEnum::SPECIFIC_PRODUCT,
                                DiscountTargetEnum::PRODUCT_VARIANT,
                            ])
                            ->where('course_quantity', $compare, 1);
                    });
            });

        if ($with) {
            $data = $data->with($with);
        }

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    public function getCoursePriceBasedOnPromotion(array $courseIds = [], array $courseCollectionIds = [], array $courseCategoriesIds = [])
    {
        $data = $this->model
            ->where('type', DiscountTypeEnum::PROMOTION)
            ->where('start_date', '<=', Carbon::now())
            ->where(function (EloquentBuilder $query) use ($courseIds, $courseCollectionIds, $courseCategoriesIds) {
                return $query
                    ->where(function (EloquentBuilder $sub) use ($courseIds) {
                        return $sub
                            ->whereIn('target', [DiscountTargetEnum::SPECIFIC_PRODUCT, DiscountTargetEnum::PRODUCT_VARIANT])
                            ->whereHas('courses', function ($whereHas) use ($courseIds) {
                                return $whereHas->whereIn('ec_discount_courses.course_id', $courseIds);
                            });
                    })
                    ->orWhere(function (EloquentBuilder $sub) use ($courseCollectionIds) {
                        return $sub
                            ->where('target', DiscountTargetEnum::PRODUCT_COLLECTIONS)
                            ->whereHas('courseCollections', function (EloquentBuilder $whereHas) use ($courseCollectionIds) {
                                return $whereHas->whereIn('ec_discount_course_collections.course_collection_id', $courseCollectionIds);
                            });
                    })
                    ->orWhere(function (EloquentBuilder $sub) use ($courseCategoriesIds) {
                        return $sub
                            ->where('target', DiscountTargetEnum::PRODUCT_CATEGORIES)
                            ->whereHas('courseCategories', function (EloquentBuilder $whereHas) use ($courseCategoriesIds) {
                                return $whereHas->whereIn('ec_discount_course_categories.course_collection_id', $courseCategoriesIds);
                            });
                    })
                    ->orWhere(function (EloquentBuilder $sub) {
                        return $sub
                            ->where('target', DiscountTargetEnum::CUSTOMER)
                            ->whereHas('customers', function (EloquentBuilder $whereHas) {
                                $customerId = auth('customer')->check() ? auth('customer')->id() : -1;

                                return $whereHas->where('ec_discount_customers.customer_id', $customerId);
                            });
                    });
            })
            ->where(function (EloquentBuilder $query) {
                return $query
                    ->whereNull('end_date')
                    ->orWhere('end_date', '>=', Carbon::now());
            })
            ->where('course_quantity', 1)
            ->select('ec_discounts.*');

        return $this->applyBeforeExecuteQuery($data, true)->get();
    }
}
