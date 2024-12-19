<?php

namespace Botble\Course\Http\Controllers;

use Botble\Base\Http\Actions\DeleteResourceAction;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Supports\Breadcrumb;
use Botble\Course\Forms\CourseForm;
use Botble\Course\Http\Requests\CourseRequest;
use Botble\Course\Models\Course;
use Botble\Course\Models\RelatedCourse;
use Botble\Course\Tables\CourseTable;
use Botble\Course\Facades\CourseHelper;
use Botble\Course\Traits\CourseActionsTrait;


class CourseController extends BaseController
{
    use CourseActionsTrait;

    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/course::course.name'), route('course.index'));
    }

    public function index(CourseTable $table)
    {
        $this->pageTitle(trans('plugins/course::course.name'));

        return $table->renderTable();
    }

    public function create()
    {
        $this->pageTitle(trans('plugins/course::course.create'));

        return CourseForm::create()->renderForm();
    }

    public function store(CourseRequest $request)
    {
        $form = CourseForm::create()->setRequest($request);
        $response = $form->save();
        $courseId = $form->getModel()->getKey();
        
        if (isset($courseId) && $courseId > 0) {
            $relatedCourses = $request->get('related_course', []);
            if (!empty($relatedCourses)) {
                $relatedCoursesData = [];
                foreach ($relatedCourses as $val) {
                    if ($val > 0) {
                        $relatedCoursesData[] = [
                            'from_course_id' => $courseId,
                            'to_course_id' => $val,
                            'created_at' => now(),  //
                            'updated_at' => now(),
                        ];
                    }
                }
                if (!empty($relatedCoursesData)) {
                    RelatedCourse::insert($relatedCoursesData);
                }
            }
        }
        return $this
            ->httpResponse()
            ->setPreviousRoute('course.index')
            ->setNextRoute('course.edit', $form->getModel()->getKey())
            ->withCreatedSuccessMessage();
    }

    public function edit(Course $course)
    {
        $this->pageTitle(trans('core/base::forms.edit_item', ['name' => $course->question]));

        return CourseForm::createFromModel($course)->renderForm();
    }

    public function update(Course $course, CourseRequest $request)
    {
       $response = CourseForm::createFromModel($course)->setRequest($request)->save();
       $courseId = $response->getModel()->getKey();
        if (isset($courseId) && $courseId > 0) {
            $relatedCourses = $request->get('related_course', []);
            if (!empty($relatedCourses)) {
                $relatedCoursesData = [];
                RelatedCourse::where('from_course_id',$course->id)->delete();
                foreach ($relatedCourses as $val) {
                    if ($val > 0) {
                        $relatedCoursesData[] = [
                            'from_course_id' => $courseId,
                            'to_course_id' => $val,
                            'created_at' => now(),  //
                            'updated_at' => now(),
                        ];
                    }
                }
                if (!empty($relatedCoursesData)) {
                    RelatedCourse::insert($relatedCoursesData);
                }
            }else{
                RelatedCourse::where('from_course_id',$course->id)->delete();
            }
        }
        return $this
            ->httpResponse()
            ->setPreviousRoute('course.index')
            ->withUpdatedSuccessMessage();
    }

    public function destroy(Course $course)
    {
        return DeleteResourceAction::make($course);
    }

    // public function getRelationBoxes(int|string|null $id, BaseHttpResponse $response): BaseHttpResponse
    // {
    //     if (! CourseHelper::isEnabledCrossSaleCourses() && ! CourseHelper::isEnabledRelatedCourses()) {
    //         return $response->setData('');
    //     }

    //     $course = null;

    //     if ($id) {
    //         $with = [];

    //         if (CourseHelper::isEnabledCrossSaleCourses()) {
    //             $with[] = 'crossSales';
    //         }

    //         if (CourseHelper::isEnabledRelatedCourses()) {
    //             $with[] = 'courses';
    //         }

    //         $course = Course::query()->with($with)->find($id);
    //     }

    //     $dataUrl = route(
    //         'courses.get-list-course-for-search',
    //         ['course_id' => $course?->getKey()]
    //     );

    //     return $response->setData(
    //         view(
    //             'plugins/course::courses.partials.extras',
    //             compact('course', 'dataUrl')
    //         )->render()
    //     );
    // }

    
}
