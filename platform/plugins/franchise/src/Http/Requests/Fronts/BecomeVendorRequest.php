<?php

namespace Botble\Franchise\Http\Requests\Fronts;

use Botble\Base\Facades\BaseHelper;
use Botble\Franchise\Facades\FranchiseHelper;
use Botble\Support\Http\Requests\Request;

class BecomeVendorRequest extends Request
{
    public function rules(): array
    {
        $maxSize = (FranchiseHelper::maxFilesizeUploadByVendor() ?: 2) * 1024;

        return [
            'shop_name' => ['required', 'string', 'min:2'],
            'shop_phone' => ['required', 'string', ...BaseHelper::getPhoneValidationRule(true)],
            'shop_url' => ['required', 'string', 'max:200'],
            'agree_terms_and_policy' => ['sometimes', 'accepted:1'],
            'certificate_file' => ['sometimes', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:' . $maxSize],
            'government_id_file' => ['sometimes', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:' . $maxSize],
        ];
    }
}
