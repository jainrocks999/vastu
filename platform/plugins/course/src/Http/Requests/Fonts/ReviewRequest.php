<?php

namespace Botble\Course\Http\Requests\Fronts;

use Botble\Course\Facades\CourseHelper;
use Botble\Support\Http\Requests\Request;

class ReviewRequest extends Request
{
    public function rules(): array
    {
        return [
            'course_id' => ['required', 'exists:ec_courses,id'],
            'star' => ['required', 'numeric', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:5000'],
            'images' => 'array|max:' . CourseHelper::reviewMaxFileNumber(),
            'images.*' => 'image|mimes:jpg,jpeg,png|max:' . CourseHelper::reviewMaxFileSize(true),
        ];
    }
}
