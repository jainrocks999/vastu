<?php

namespace Botble\Course\Http\Controllers;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Course\Models\CourseReview;

class PublishedCourseReviewController extends BaseController
{
    public function store(string|int $id)
    {
        $review = CourseReview::query()
            ->whereIn('status', [BaseStatusEnum::DRAFT, BaseStatusEnum::PENDING])
            ->findOrFail($id);

        $review->update(['status' => BaseStatusEnum::PUBLISHED]);

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/course::course-review.published_success'));
    }

    public function destroy(string|int $id)
    {
        $review = CourseReview::query()
            ->wherePublished()
            ->findOrFail($id);

        $review->update(['status' => BaseStatusEnum::DRAFT]);

        return $this
            ->httpResponse()
            ->setMessage(trans('plugins/course::course-review.unpublished_success'));
    }
}
