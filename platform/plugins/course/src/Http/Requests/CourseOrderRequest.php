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


class CourseOrderRequest extends Request
{

    public function rules(): array
    {
        return [
            'payment_transaction_id' => ['numeric','required','min:0'],
            'user_id' => ['numeric','required','min:0'],
            'price' => ['numeric','nullable','min:0'],
            'discounts' => ['nullable','integer'],
            'payment_method' => ['string','required','min:0'],
            'payment_status' => ['string','required','min:0'],
            'status' => ['required'],
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
            'payment_transaction_id.required' => 'The Transaction id field is required.',
            'user_id.required' => 'The student name field is required.',
        ];
    }

}
