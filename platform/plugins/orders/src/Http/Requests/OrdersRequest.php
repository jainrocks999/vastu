<?php

namespace Botble\Orders\Http\Requests;

use Botble\Orders\Facades\OrdersManager;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class OrdersRequest extends Request
{
    public function rules(): array
    {
        return [
            'name' => 'required',
            'key' => 'required|max:120|unique:orders,key,' . $this->route('orders.id'),
            'location' => 'sometimes|' . Rule::in(array_keys(OrdersManager::getLocations())),
            'order' => 'required|integer|min:0|max:127',
            'status' => Rule::in(BaseStatusEnum::values()),
            'expired_at' => 'required|date',
            'orders_type' => 'required|in:custom_ad,google_ordersense',
            'google_ordersense_slot_id' => 'nullable|string|max:255',
        ];
    }
}
