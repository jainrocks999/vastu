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


class TrainerRequest extends Request
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
            'subtitle' => ['required', 'string'],
            'description' => ['required', 'string', 'max:300000'],
        
            'image' => ['nullable'],
          //  'video' => ['sometimes'],
          
            'status' => ['required', Rule::in(BaseStatusEnum::values())],
        ];

       
        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => trans('plugins/course::course.course_create_validate_name_required'),
        ];
    }

}
