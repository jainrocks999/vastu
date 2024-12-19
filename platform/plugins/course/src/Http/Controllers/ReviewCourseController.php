<?php

namespace Botble\Course\Http\Controllers;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Facades\Assets;
use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Requests\SelectSearchAjaxRequest;
use Botble\Base\Supports\Breadcrumb;
use Botble\Course\Forms\CourseReviewForm;
use Botble\Course\Http\Requests\ReviewRequest;
use Botble\Course\Models\Customer;
use Botble\Course\Models\Course;
use Botble\Course\Models\CourseReview;
use Botble\Course\Tables\CourseReviewTable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewCourseController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/course::course-review.name'), route('course_review.index'));
    }

    public function index(CourseReviewTable $dataTable)
    {
        $this->pageTitle(trans('plugins/course::course-review.name'));
        Assets::addStylesDirectly('vendor/core/plugins/course/css/review.css');
        return $dataTable->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/course::course-review.create_review'));

        return CourseReviewForm::create()->renderForm();
    }

    public function store(ReviewRequest $request)
    {
        if (! $request->filled('customer_id') && ! $request->filled('customer_name') && ! $request->filled('customer_email')) {
            return $this
                ->httpResponse()
                ->setError()
                ->withInput()
                ->setMessage(trans('plugins/course::course-review.choose_customer_help'));
        }

        $customer = Customer::find($request->input('customer_id'));
        if ($request->input('customer_id')) {
            $request->merge([
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
            ]);
        } else {
            $request->merge([
                'customer_id' => $request->input('customer_id'),
            ]);
        }

        $review = CourseReview::query()
            ->where('course_id', $request->input('course_id'))
            ->where(function (Builder $query) use ($request) {
                $query
                    ->whereNotNull('customer_id')
                    ->where('customer_id', $request->input('customer_id'));
            })
            ->exists();

        if ($review) {
            return $this
                ->httpResponse()
                ->setError()
                ->withInput()
                ->setMessage(trans('plugins/course::course-review.review_already_exists'));
        }
        $review = CourseReview::query()->forceCreate($request->validated());

        event(new CreatedContentEvent('review', $request, $review));

        return $this
            ->httpResponse()
            ->setNextRoute('course_review.show', $review)
            ->withCreatedSuccessMessage();
    }

    public function show(CourseReview $review): View
    {

        Assets::addScriptsDirectly('vendor/core/plugins/course/js/admin-review.js')
            ->addStylesDirectly('vendor/core/plugins/course/css/review.css');

        $review->load([
            'user',
            'reply',
            'reply.user',
            'course' => fn (BelongsTo $query) => $query
                ->withCount('reviews')
                ->withAvg('reviews', 'star'),
        ]);

        $this->pageTitle(trans('plugins/course::course-review.view', ['name' => $review->user->name ?: $review->customer_name]));

        return view('plugins/course::course-reviews.show', compact('review'));
    }

    public function destroy(CourseReview $review, $id)
    {
        CourseReview::where('id',$id)->delete();
        return $this
        ->httpResponse()
        ->setNextUrl(route('course_review.index'))
        ->withDeletedSuccessMessage();
    }

    public function ajaxSearchCustomers(SelectSearchAjaxRequest $request)
    {
        $customers = Customer::query()
            ->where(function (Builder $query) use ($request) {
                $keyword = "%{$request->input('search')}%";

                $query
                    ->where('name', 'LIKE', $keyword)
                    ->orWhere('email', 'LIKE', $keyword);
            })
            ->select('id', 'name')
            ->paginate();

        return $this
            ->httpResponse()
            ->setData($customers);
    }

    public function ajaxSearchCourses(SelectSearchAjaxRequest $request)
    {
        $courses = Course::query()
            ->where('title', 'LIKE', "%{$request->input('search')}%")
            ->select('id', 'title')
            ->paginate();

        return $this
            ->httpResponse()
            ->setData($courses);
    }
}
