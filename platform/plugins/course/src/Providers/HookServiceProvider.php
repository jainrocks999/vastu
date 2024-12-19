<?php

namespace Botble\Course\Providers;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Botble\Base\Facades\MetaBox;
use Botble\Base\Models\BaseModel;
use Botble\Base\Supports\ServiceProvider;
use Botble\Course\Contracts\Course as CourseContract;
use Botble\Course\CourseCollection;
use Botble\Course\CourseItem;
use Botble\Course\CourseSupport;
use Botble\Course\Models\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        add_action(BASE_ACTION_META_BOXES, function (string $context, array|string|Model|null $object = null): void {
            if (
                ! $object instanceof BaseModel
                || $context != 'advanced'
                || ! in_array($object::class, config('plugins.course.general.schema_supported', []))
                || ! setting('enable_course_schema', 0)
            ) {
                return;
            }

            MetaBox::addMetaBox(
                'course_schema_config_wrapper',
                trans('plugins/course::course.course_schema_config', [
                    'link' => Html::link(
                        'https://developers.google.com/search/docs/data-types/coursepage',
                        trans('plugins/course::course.learn_more'),
                        ['target' => '_blank']
                    ),
                ]),
                function () {
                    return (new CourseSupport())->renderMetaBox(func_get_args()[0] ?? null);
                },
                $object::class,
                $context
            );
        }, 39, 2);

        add_action(BASE_ACTION_PUBLIC_RENDER_SINGLE, function ($screen, $object): void {
            if (
                ! in_array($object::class, config('plugins.course.general.schema_supported', []))
                || ! setting('enable_course_schema', 0)
            ) {
                return;
            }

            $courses = (array) $object->getMetaData('course_schema_config', true);

            if (is_plugin_active('course')) {
                $selectedExistingCourses = $object->getMetaData('course_ids', true);

                if ($selectedExistingCourses && is_array($selectedExistingCourses)) {
                    $selectedExistingCourses = array_filter($selectedExistingCourses);

                    if ($selectedExistingCourses) {
                        $selectedCourses = Course::query()
                            ->wherePublished()
                            ->whereIn('id', $selectedExistingCourses)
                            ->pluck('answer', 'question')
                            ->all();

                        foreach ($selectedCourses as $question => $answer) {
                            $courses[] = [
                                [
                                    'key' => 'question',
                                    'value' => $question,
                                ],
                                [
                                    'key' => 'answer',
                                    'value' => $answer,
                                ],
                            ];
                        }
                    }
                }
            }

            $courses = array_filter($courses);

            if (empty($courses)) {
                return;
            }

            foreach ($courses as $key => $item) {
                if (! $item[0]['value'] && ! $item[1]['value']) {
                    Arr::forget($value, $key);
                }
            }

            $schemaItems = new CourseCollection();

            foreach ($courses as $item) {
                $schemaItems->push(
                    new CourseItem(BaseHelper::clean($item[0]['value']), BaseHelper::clean($item[1]['value']))
                );
            }

            app(CourseContract::class)->registerSchema($schemaItems);
        }, 39, 2);
    }
}
