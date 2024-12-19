<?php

namespace Botble\Franchise\Http\Requests;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;
use Botble\Base\Rules\MediaImageRule;
use Botble\Franchise\Facades\FranchiseHelper;
use Botble\Franchise\Models\Franchise;
use Botble\Media\Facades\RvMedia;
use Carbon\Carbon;


class FranchiseServiceRequest extends Request
{
    public function rules(): array
    {
        return [
            'services_name' =>['required', 'string'],
            'services_desc' =>['nullable', 'string'],
            'services_price' =>['required', 'integer'],
            'status' =>['required', 'string']
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'name.required' => trans('plugins/franchise::franchise.course_create_validate_name_required'),
    //     ];
    // }

}
