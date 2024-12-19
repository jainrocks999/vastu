<?php

namespace Botble\Course\Forms;

use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\TextareaFieldOption;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\EmailField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\MediaImagesField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Course\Forms\Fronts\Auth\FieldOptions\EmailFieldOption;
use Botble\Course\Forms\Fronts\Auth\FieldOptions\TextFieldOption;
use Botble\Course\Http\Requests\ReviewRequest;
use Botble\Course\Models\CourseReview;
use Botble\Course\Models\Course;
use Carbon\Carbon;

class CourseReviewForm extends FormAbstract
{
    public function setup(): void
    {

        $courses = Course::query()->pluck('title','id')->all();
        
        $this
            ->model(CourseReview::class)
            ->setValidatorClass(ReviewRequest::class)
            // ->add(
            //     'course_id',
            //     SelectField::class,
            //     SelectFieldOption::make()
            //         ->label(trans('plugins/course::course-review.course'))
            //         ->ajaxSearch()
            //         ->required()
            //         ->ajaxUrl(route('course_review.ajax-search-courses'))
            // )
            ->add(
                'course_id',
                SelectField::class,
                SelectFieldOption::make()
                ->label(trans('plugins/course::course-review.course'))
                    ->choices(['' => trans('plugins/course::course.select_category')] + $courses)
                    ->addAttribute('card-body-class', 'p-0')
                    ->required()
            )
            ->add(
                'customer_id',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/course::course-review.choose_existing_customer'))
                    ->ajaxSearch()
                    ->ajaxUrl(route('course_review.ajax-search-customers'))
                    ->helperText(trans('plugins/course::course-review.choose_customer_help'))
            )
            ->add(
                'open_or',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content(sprintf(
                        '<div class="form-fieldset"><label class="form-label">%s</label>',
                        trans('plugins/course::course-review.or_enter_manually')
                    ))
            )
            ->add(
                'customer_name',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/course::course.customer_name'))
            )
            ->add(
                'customer_email',
                EmailField::class,
                EmailFieldOption::make()
                    ->label(trans('plugins/course::course.customer_email'))
            )
            ->add(
                'close_or',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->content('</div>')
            )
            ->add(
                'star',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/course::course-review.star'))
                    ->choices(array_combine(range(1, 5), range(1, 5)))
                    ->selected(5)
            )
            ->add(
                'comment',
                TextareaField::class,
                TextareaFieldOption::make()
                    ->label(trans('plugins/course::course-review.comment'))
                    ->required()
            )
            ->add('images[]', MediaImagesField::class, [
                'label' => trans('plugins/course::course-review.images'),
                'values' => $this->model->images,
            ])
            ->add(
                'created_at',
                DatePickerField::class,
                DatePickerFieldOption::make()
                    ->label(trans('core/base::tables.created_at'))
                    ->value(Carbon::now())
                    ->withTimePicker()
            )
            ->setBreakFieldPoint('created_at');
    }
}
