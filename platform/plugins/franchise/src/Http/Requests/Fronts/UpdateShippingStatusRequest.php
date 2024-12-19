<?php

namespace Botble\Franchise\Http\Requests\Fronts;

use Botble\Ecommerce\Enums\ShippingStatusEnum;
use Botble\Franchise\Facades\FranchiseHelper;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class UpdateShippingStatusRequest extends Request
{
    public function rules(): array
    {
        if (FranchiseHelper::allowVendorManageShipping()) {
            return [
                'status' => Rule::in(ShippingStatusEnum::values()),
            ];
        }

        return [
            'status' => Rule::in([ShippingStatusEnum::ARRANGE_SHIPMENT, ShippingStatusEnum::READY_TO_BE_SHIPPED_OUT]),
        ];
    }
}
