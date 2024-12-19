<?php

namespace Botble\Course\Forms\Settings;

use Botble\Course\Http\Requests\Settings\CourseSettingRequest;
use Botble\Setting\Forms\SettingForm;

class CourseSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/course::course.settings.title'))
            ->setSectionDescription(trans('plugins/course::course.settings.description'))
            ->setValidatorClass(CourseSettingRequest::class)
            ->add('enable_course_schema', 'onOffCheckbox', [
                'label' => trans('plugins/course::course.settings.enable_course_schema'),
                'value' => setting('enable_course_schema', false),
                'wrapper' => [
                    'class' => 'mb-0',
                ],
                'help_block' => [
                    'text' => trans('plugins/course::course.settings.enable_course_schema_description', [
                        'url' => sprintf('<a href="%s">%s</a>', $url = 'https://developers.google.com/search/docs/data-types/coursepage', $url),
                    ]),
                ],
            ]);
    }
}
