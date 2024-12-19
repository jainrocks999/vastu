<?php

namespace Botble\Course\Http\Controllers\Settings;

use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Course\Forms\Settings\CourseSettingForm;
use Botble\Course\Http\Requests\Settings\CourseSettingRequest;
use Botble\Setting\Http\Controllers\SettingController;

class CourseSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/course::course.settings.title'));

        return CourseSettingForm::create()->renderForm();
    }

    public function update(CourseSettingRequest $request): BaseHttpResponse
    {
        return $this->performUpdate($request->validated());
    }
}
