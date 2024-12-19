<?php

namespace Botble\Course\Forms;

use Botble\Base\Forms\FieldOptions\DescriptionFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextareaField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Botble\Course\Http\Requests\CourseCategoryRequest;
use Botble\Course\Models\CourseCategory;

class CourseCategoryForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(CourseCategory::class)
            ->setValidatorClass(CourseCategoryRequest::class)
            ->add('name', TextField::class,
                 NameFieldOption::make()
                 ->label(trans('plugins/course::course-category.name'))
                 ->required())
            ->add('description', TextareaField::class, DescriptionFieldOption::make()->label(trans('plugins/course::course-category.description')))
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->setBreakFieldPoint('status');
    }
}
