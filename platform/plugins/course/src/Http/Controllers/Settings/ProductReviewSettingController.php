<?php

namespace Botble\Course\Http\Controllers\Settings;

use Botble\Course\Forms\Settings\CourseReviewSettingForm;
use Botble\Course\Http\Requests\Settings\CourseReviewSettingRequest;

class CourseReviewSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/course::setting.course_review.name'));

        return CourseReviewSettingForm::create()->renderForm();
    }

    public function update(CourseReviewSettingRequest $request)
    {
        return $this->performUpdate($request->validated());
    }
}
