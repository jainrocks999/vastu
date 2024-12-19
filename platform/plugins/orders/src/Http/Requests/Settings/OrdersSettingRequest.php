<?php

namespace Botble\Orders\Http\Requests\Settings;

use Botble\Support\Http\Requests\Request;

class OrdersSettingRequest extends Request
{
    public function rules(): array
    {
        return [
            'orders_google_ordersense_auto_orders' => ['nullable', 'string'],
            'orders_google_ordersense_unit_client_id' => ['nullable', 'string'],
            'orders_google_ordersense_txt_file' => ['nullable', 'file', 'mimes:txt', 'max:2048'],
        ];
    }
}
