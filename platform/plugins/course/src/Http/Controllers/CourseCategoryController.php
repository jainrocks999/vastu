<?php

namespace Botble\Course\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\Course\Forms\CourseCategoryForm;
use Botble\Course\Http\Requests\CourseCategoryRequest;
use Botble\Course\Models\CourseCategory;
use Botble\Course\Tables\CourseCategoryTable;

class CourseCategoryController extends BaseController
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/course::course.name'))
            ->add(trans('plugins/course::course-category.name'), route('course_category.index'));
    }

    public function index(CourseCategoryTable $table)
    {
        $this->pageTitle(trans('plugins/course::course-category.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/course::course-category.create'));

        return CourseCategoryForm::create()->renderForm();
    }

    public function store(CourseCategoryRequest $request)
    {
        $form = CourseCategoryForm::create()->setRequest($request);
        $form->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('course_category.index')
            ->setNextRoute('course_category.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(CourseCategory $courseCategory)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $courseCategory->name]));

        return CourseCategoryForm::createFromModel($courseCategory)->renderForm();
    }

    public function update(CourseCategory $courseCategory, CourseCategoryRequest $request)
    {
        CourseCategoryForm::createFromModel($courseCategory)->setRequest($request)->save();

        return $this
            ->httpResponse()
            ->setPreviousRoute('course_category.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(CourseCategory $courseCategory)
    {
        return DeleteResourceAction::make($courseCategory);
    }

    public function getListForSelect()
    {
        $categories = CourseCategory::query()
            ->toBase()
            ->select([
                'id',
                'name',
            ])
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->get();

        return $this
            ->httpResponse()
            ->setData($categories);
    }
}
