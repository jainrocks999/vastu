<?php

namespace Botble\Course\Http\Controllers;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\Assets;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Supports\Breadcrumb;
use Botble\Course\Http\Requests\CourseDiscountRequest;
use Botble\Course\Models\CourseDiscount;
use Botble\Course\Models\Course;
use Botble\Ecommerce\Models\Customer;
use Botble\Course\Tables\CourseDiscountTable;
use Botble\JsValidation\Facades\JsValidator;
use Botble\Media\Facades\RvMedia;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Botble\Course\Traits\CourseActionsTrait;


class CourseDiscountController extends BaseController
{
    use CourseActionsTrait;
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/course::course-discount.name'), route('discounts.index'));
    }

    public function index(CourseDiscountTable $dataTable)
    {
        $this->pageTitle(trans('plugins/course::course-discount.name'));

        Assets::addStylesDirectly(['vendor/core/plugins/course/css/course.css']);

        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/course::course-discount.create'));

        Assets::usingVueJS()
            ->addStylesDirectly('vendor/core/plugins/course/css/course.css')
            ->addScriptsDirectly('vendor/core/plugins/course/js/discount.js')
            ->addScripts(['timepicker', 'input-mask', 'form-validation'])
            ->addStyles('timepicker');

        $jsValidation = JsValidator::formRequest(CourseDiscountRequest::class);
        return view('plugins/course::course_discount.create', compact('jsValidation'));
    }

    public function store(CourseDiscountRequest $request)
    {
        /**
         * @var CourseDiscount $discount
         */
        $discount = CourseDiscount::query()->create($request->validated());

        if ($discount) {

            if (($courseCategories = $request->input('course_categories')) && ! is_array($courseCategories)) {
                $courseCategories = [$courseCategories];
                $discount->courseCategories()->attach($courseCategories);
            }

            if ($courses = $request->input('courses')) {
                if (is_string($courses) && Str::contains($courses, ',')) {
                    $courses = explode(',', $courses);
                }

                if (! is_array($courses)) {
                    $courses = [$courses];
                }

                foreach ($courses as $courseId) {
                    /**
                     * @var Course $course
                     */
                    $course = Course::query()->find($courseId);

                    if (! $course || $course->is_variation) {
                        Arr::forget($courses, $courseId);
                    }

                    $courses = array_merge($courses, $course->variations()->pluck('course_id')->all());
                }

                $discount->courses()->attach(array_unique($courses));
            }

            // if ($variants = $request->input('variants')) {
            //     if (is_string($variants) && Str::contains($variants, ',')) {
            //         $variants = explode(',', $variants);
            //     }

            //     if (! is_array($variants)) {
            //         $variants = [$variants];
            //     }

            //     foreach ($variants as $variantId) {
            //         $course = Course::query()->find($variantId);

            //         if (! $course || ! $course->is_variation || ! $course->original_course->id) {
            //             Arr::forget($courses, $course->getKey());
            //         }

            //         $variants = array_merge($variants, [$course->original_course->id]);
            //     }

            //     $discount->courses()->attach(array_unique($variants));
            // }

            if ($customers = $request->input('customers')) {
                if (is_string($customers) && Str::contains($customers, ',')) {
                    $customers = explode(',', $customers);
                }

                if (! is_array($customers)) {
                    $customers = [$customers];
                }

                $discount->customers()->attach(array_unique($customers));
            }
        }

        event(new CreatedContentEvent(DISCOUNT_MODULE_SCREEN_NAME, $request, $discount));

        return $this
            ->httpResponse()
            ->setNextUrl(route('course_discount.index'))
            ->withCreatedSuccessMessage();
    }

    public function edit(CourseDiscount $discount)
    {
        $discount->load([
            'customers',
            'courseCollections',
            'courseCategories',
            'courses' => fn (BelongsToMany $query) => $query->where('is_variation', false),
            'courseVariants.variationInfo.variationItems.attribute',
        ]);

        foreach ($discount->courseVariants as $courseVariant) {
            $courseVariant->image_url = RvMedia::getImageUrl($courseVariant->image, 'thumb', false, RvMedia::getDefaultImage());
            $courseVariant->price = $courseVariant->variationInfo->course->front_sale_price;
            foreach ($courseVariant->variationInfo->variationItems as $variationItem) {
                $variationItem->attribute_title = $variationItem->attribute->title;
            }

            $courseVariant->variationItems = $courseVariant->variationInfo->variationItems;
        }

        $discount->courses->each(function ($course) {
            $course->image_url = RvMedia::getImageUrl($course->image, 'thumb', false, RvMedia::getDefaultImage());
        });

        $discount->customers->each(function ($customer) {
            $customer->avatar_url = RvMedia::getImageUrl($customer->avatar, 'thumb', false, RvMedia::getDefaultImage());
        });

        $this->pageTitle(trans('plugins/course::course-discount.edit'));

        Assets::usingVueJS()
            ->addStylesDirectly('vendor/core/plugins/course/css/course.css')
            ->addScriptsDirectly('vendor/core/plugins/course/js/discount.js')
            ->addScripts(['timepicker', 'input-mask', 'form-validation'])
            ->addStyles('timepicker');

        return view('plugins/course::course_discount.edit', compact('discount'));
    }

    public function update(CourseDiscount $discount, CourseDiscountRequest $request)
    {
        $discount->update($request->validated());

        if ($courseCollections = $request->input('course_collections')) {
            if (! is_array($courseCollections)) {
                $courseCollections = [$courseCollections];
                $discount->courseCollections()->sync($courseCollections);
            }
        }

        if (($courseCategories = $request->input('course_categories')) && ! is_array($courseCategories)) {
            $courseCategories = [$courseCategories];
            $discount->courseCategories()->sync($courseCategories);
        }

        if ($courses = $request->input('courses')) {
            if (is_string($courses) && Str::contains($courses, ',')) {
                $courses = explode(',', $courses);
            }

            if (! is_array($courses)) {
                $courses = [$courses];
            }

            foreach ($courses as $courseId) {
                /**
                 * @var Course $course
                 */
                $course = Course::query()->find($courseId);

                if (! $course || $course->is_variation) {
                    Arr::forget($courses, $courseId);
                }

                $courses = array_merge($courses, $course->variations()->pluck('course_id')->all());
            }

            $discount->courses()->sync(array_unique($courses));
        } else {
            $discount->courses()->detach();
        }

        if ($variants = $request->input('variants')) {
            if (is_string($variants) && Str::contains($variants, ',')) {
                $variants = explode(',', $variants);
            }

            if (! is_array($variants)) {
                $variants = [$variants];
            }

            foreach ($variants as $variantId) {
                $course = Course::query()->find($variantId);

                if (! $course || ! $course->is_variation || ! $course->original_course->id) {
                    Arr::forget($courses, $course->id);
                }

                $variants = array_merge($variants, [$course->original_course->id]);
            }

            $discount->courses()->sync(array_unique($variants));
        }

        if ($customers = $request->input('customers')) {
            if (is_string($customers) && Str::contains($customers, ',')) {
                $customers = explode(',', $customers);
            }

            if (! is_array($customers)) {
                $customers = [$customers];
            }

            $discount->customers()->sync(array_unique($customers));
        } else {
            $discount->customers()->detach();
        }

        event(new UpdatedContentEvent(DISCOUNT_MODULE_SCREEN_NAME, $request, $discount));

        return $this
            ->httpResponse()
            ->setNextUrl(route('discounts.edit', $discount))
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CourseDiscount $discount, $id)
    {
          CourseDiscount::where('id',$id)->delete();
          return $this
          ->httpResponse()
          ->setNextUrl(route('course_discount.index'))
          ->withDeletedSuccessMessage();
    }

    public function postGenerateCoupon()
    {
        do {
            $code = strtoupper(Str::random(12));
        } while (CourseDiscount::query()->where(['code' => $code])->exists());

        return $this
            ->httpResponse()
            ->setData($code);
    }
}
