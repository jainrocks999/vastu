<?php

namespace Botble\Course\Listeners;

use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Facades\MetaBox;
use Botble\Course\CourseSupport;

class SaveCourseListener
{
    public function handle(CreatedContentEvent|UpdatedContentEvent $event): void
    {
        if (! setting('enable_course_schema')) {
            return;
        }

        $request = $event->request;
        $model = $event->data;

        if (! is_object($model) || ! in_array($model::class, config('plugins.course.general.schema_supported', []))) {
            return;
        }

        if ($request->has('content') && $request->has('course_schema_config')) {
            (new CourseSupport())->saveConfigs($model, $request->input('course_schema_config'));
        }

        MetaBox::saveMetaBoxData($model, 'course_ids', $request->input('selected_existing_courses', []));
    }
}
