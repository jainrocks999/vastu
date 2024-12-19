<?php

namespace Botble\Course;

use Botble\PluginManagement\Abstracts\PluginOperationAbstract;
use Botble\Setting\Facades\Setting;
use Illuminate\Support\Facades\Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove(): void
    {
        Schema::dropIfExists('course_categories');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('course_related_relations');
        Schema::dropIfExists('course_discount_course');
        Schema::dropIfExists('course_discount_student');
        Schema::dropIfExists('course_categories_translations');
        Schema::dropIfExists('courses_translations');
        Setting::delete([
            'enable_course_schema',
        ]);
    }
}
