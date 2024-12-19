<?php

namespace Botble\Course\Http\Controllers\Fronts;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Course\Facades\CourseHelper;
use Botble\Course\Http\Requests\Fronts\ReviewRequest;
use Botble\Course\Models\Course;
use Botble\Course\Models\Review;
use Botble\Course\Traits\CheckReviewConditionTrait;
use Botble\Media\Facades\RvMedia;
use Botble\SeoHelper\Facades\SeoHelper;
use Botble\Slug\Facades\SlugHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ReviewController extends BaseController
{
    use CheckReviewConditionTrait;

    public function store(ReviewRequest $request)
    {
        if (! CourseHelper::isReviewEnabled()) {
            abort(404);
        }

        $courseId = $request->input('course_id');
        $check = $this->checkReviewCondition($courseId);

        if (Arr::get($check, 'error')) {
            return $this
                ->httpResponse()
                ->setError()
                ->setMessage(Arr::get($check, 'message', __('Oops! Something Went Wrong.')));
        }

        $results = [];
        if ($request->hasFile('images')) {
            $images = (array) $request->file('images', []);
            foreach ($images as $image) {
                $result = RvMedia::handleUpload($image, 0, 'reviews');
                if ($result['error']) {
                    return $this
                        ->httpResponse()
                        ->setError()
                        ->setMessage($result['message']);
                }

                $results[] = $result;
            }
        }

        Review::query()->create([
            ...$request->validated(),
            'customer_id' => auth('customer')->id(),
            'images' => $results ? collect($results)->pluck('data.url')->values()->all() : null,
            'status' => get_course_setting('review_need_to_be_approved', false) ? BaseStatusEnum::PENDING : BaseStatusEnum::PUBLISHED,
        ]);

        return $this
            ->httpResponse()
            ->setMessage(__('Added review successfully!'));
    }

    public function destroy(int|string $id)
    {
        if (! CourseHelper::isReviewEnabled()) {
            abort(404);
        }

        $review = Review::query()->findOrFail($id);

        if (auth()->check() || (auth('customer')->check() && auth('customer')->id() == $review->customer_id)) {
            $review->delete();

            return $this
                ->httpResponse()
                ->setMessage(__('Deleted review successfully!'));
        }

        abort(401);
    }

    public function getCourseReview(string $key)
    {
        if (! CourseHelper::isReviewEnabled()) {
            abort(404);
        }

        $slug = SlugHelper::getSlug($key, SlugHelper::getPrefix(Course::class));

        if (! $slug) {
            abort(404);
        }

        $condition = [
            'ec_courses.id' => $slug->reference_id,
        ];

        $course = get_courses(array_merge([
                'condition' => $condition,
                'take' => 1,
            ], CourseHelper::withReviewsParams()));

        if (! $course) {
            abort(404);
        }

        $check = $this->checkReviewCondition($course->id);
        if (Arr::get($check, 'error')) {
            return $this
                ->httpResponse()
                ->setNextUrl($course->url)
                ->setError()
                ->setMessage(Arr::get($check, 'message', __('Oops! Something Went Wrong.')));
        }

        Theme::asset()
            ->add('course-review-css', 'vendor/core/plugins/course/css/review.css');
        Theme::asset()->container('footer')
            ->add('course-review-js', 'vendor/core/plugins/course/js/review.js', ['jquery']);

        SeoHelper::setTitle(__('Review course ":course"', ['course' => $course->name]))->setDescription($course->description);

        Theme::breadcrumb()
            ->add(__('Courses'), route('public.courses'))
            ->add($course->name, $course->url)
            ->add(__('Review'));

        do_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, PRODUCT_MODULE_SCREEN_NAME, $course);

        return Theme::scope('course.course-review', compact('course'), 'plugins/course::themes.includes.reviews')
            ->render();
    }

    public function ajaxReviews(int|string $id, Request $request)
    {
        /**
         * @var Course $course
         */
        $course = Course::query()
            ->wherePublished()
            ->where([
                'id' => $id,
                'is_variation' => false,
            ])
            ->with(['variations'])
            ->firstOrFail();

        $star = $request->integer('star');
        $perPage = $request->integer('per_page', 10);

        $reviews = CourseHelper::getCourseReviews($course, $star, $perPage);

        if ($star) {
            $message = __(':total review(s) ":star star" for ":course"', [
                'total' => $reviews->total(),
                'course' => $course->name,
                'star' => $star,
            ]);
        } else {
            $message = __(':total review(s) for ":course"', [
                'total' => $reviews->total(),
                'course' => $course->name,
            ]);
        }

        return $this
            ->httpResponse()
            ->setData(
                Theme::scope(
                    'course.includes.review-list',
                    compact('reviews'),
                    'plugins/course::themes.includes.review-list'
                )->getContent()
            )
            ->setMessage($message, false)
            ->toApiResponse();
    }
}
