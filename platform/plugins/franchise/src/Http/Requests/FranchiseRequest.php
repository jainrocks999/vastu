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


class FranchiseRequest extends Request
{
    public function rules(): array
    {
        return [
            'customer_id' =>['nullable', 'integer'],
            'service_id' =>['required', 'integer'],
            'franchise_name' =>['required', 'string'],
            'email' =>['required', 'string'],
            'phone' =>['required', 'string'],
            'dob' =>['required', 'string'],
            'gender' =>['required', 'string'],
            'current_location' =>['required', 'string'],
            'country' =>['required', 'string'],
            'state' =>['required', 'string'],
            'city' =>['required', 'string'],
            'pincode' =>['nullable', 'string'],
            'certificate' =>['required', 'string'],
            'certificate_docs' =>['nullable', 'string'],
            'experience_field' =>['nullable', 'string'],
            'experience_of_year' =>['required', 'string'],
            'application_req_date' =>['nullable'],
            'application_status' =>['required', 'string'],
            'specializations' =>['required', 'string'],
            'language' =>['required', 'string'],
            'level' =>['required', 'string'],
            'charges' =>['required', 'integer'],
            'logo' =>['nullable', 'string'],
            'avatar' =>['nullable', 'string'],
            'short_description' =>['nullable', 'string'],
            'content' =>['nullable', 'string'],
            'status' =>['required', 'string'],
            'confirmed_at'=> ['nullable']
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'name.required' => trans('plugins/franchise::franchise.course_create_validate_name_required'),
    //     ];
    // }

}
