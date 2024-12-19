<?php

namespace Botble\Course\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

use Botble\Base\Rules\MediaImageRule;
// use Botble\Course\Enums\CrossSellPriceType;
// use Botble\Course\Enums\GlobalOptionEnum;
// use Botble\Course\Enums\CourseTypeEnum;
use Botble\Course\Facades\CourseHelper;
use Botble\Course\Models\Course;
use Botble\Media\Facades\RvMedia;
use Carbon\Carbon;


class CourseRequest extends Request
{
    protected function prepareForValidation(): void
    {
        $options = $this->input('options');

        if (! empty($options)) {
            foreach ($options as $key => $option) {
                if (! empty($option['values'])) {
                    foreach ($option['values'] as $valueKey => $value) {
                        if (isset($value['order']) && $value['order'] === 'undefined') {
                            $options[$key]['values'][$valueKey]['order'] = 0;
                        }

                        if (isset($value['id']) && $value['id'] === 'undefined') {
                            $options[$key]['values'][$valueKey]['id'] = 0;
                        }
                    }
                }
            }
        }

        $this->merge([
            'options' => $options,
            'notify_attachment_updated' => $this->boolean('notify_attachment_updated'),
        ]);
    }
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'code' => ['required', 'string'],
            'label1' => ['required', 'string'],
            'description1' => ['required', 'string', 'max:300000'],
            'label2' => ['nullable', 'string'],
            'description2' => ['nullable', 'string', 'max:300000'],
            'label3' => ['nullable', 'string'],
            'description3' => ['nullable', 'string', 'max:300000'],
            'label4' => ['nullable', 'string'],
            'description4' => ['nullable', 'string', 'max:300000'],
            'price' => ['numeric','required','min:0'],
            'student_price' => ['numeric','required','min:0'],
            'franchise_price' => ['numeric','required','min:0'],
            'image' => ['nullable'],
            'video' => ['sometimes'],
            'docs' => ['sometimes'],
            'type' => ['required','string'], 
            'instructor' => ['nullable','string'],
            'start_date' => ['date', 'required'],
            'end_date' => ['nullable','nullable'],
            'language' => ['required'],
            'prerequisites' => ['nullable','string'],
            'enrollment_limit' => ['nullable','integer'],
            'enrollment_type' => ['nullable','string'],
            'certification' => ['nullable','integer'],
            'discounts' => ['nullable','integer'],
            'tags' => ['nullable','string'],
            'keywords' => ['nullable','string'],
            'course_category_id' => ['required', 'exists:course_category,id'],
            'status' => ['required', Rule::in(BaseStatusEnum::values())],
        ];

        if (CourseHelper::isEnabledCourseOptions()) {
            $options = $this->input('options');

            if (! empty($options)) {
                $courseOptionRules = $this->getRuleCourseOptionRequest($options);
                $rules = array_merge($rules, $courseOptionRules);
            }
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => trans('plugins/course::course.course_create_validate_name_required'),
        ];
    }

}
