<?php

namespace Botble\Course\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface DiscountInterface extends RepositoryInterface
{
    public function getAvailablePromotions(array $with = [], bool $forCourseSingle = false);

    public function getCoursePriceBasedOnPromotion(array $courseIds = [], array $courseCollectionIds = [], array $courseCategoriesIds = []);
}
