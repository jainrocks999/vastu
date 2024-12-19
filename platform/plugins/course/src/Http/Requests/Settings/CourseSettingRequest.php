<?php

namespace Botble\Course\Http\Requests\Settings;

use Botble\Base\Rules\OnOffRule;
use Botble\Support\Http\Requests\Request;

class CourseSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'enable_course_schema' => new OnOffRule(),
        ];
    }
}
