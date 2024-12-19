<?php

namespace Botble\Course\Services\Courses;

use Botble\Course\Models\Course;
use Closure;

class CourseDiscountPriceService extends CoursePriceHandlerService
{
    public function handle(Course $course, Closure $next)
    {
        $finalPrice = $course->getFinalPrice();
        $discountPrice = $course->getDiscountPrice();

        if ($discountPrice < $finalPrice) {
            $course->setFinalPrice($discountPrice);
        }

        return $next($course);
    }
}
