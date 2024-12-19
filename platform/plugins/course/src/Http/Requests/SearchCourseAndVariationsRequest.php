<?php

namespace Botble\Course\Http\Requests;

use Botble\Support\Http\Requests\Request;

class SearchCourseAndVariationsRequest extends Request
{
    public function rules(): array
    {
        return [
            'course_ids' => ['sometimes', 'array'],
            'keyword' => ['nullable', 'string', 'max:220'],
        ];
    }
}
