<?php

namespace Botble\Orders\Http\Controllers\Settings;

use Botble\Orders\Forms\Settings\OrdersSettingForm;
use Botble\Orders\Http\Requests\Settings\OrdersSettingRequest;
use Botble\Setting\Http\Controllers\SettingController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class OrdersSettingController extends SettingController
{
    public function edit()
    {
        $this->pageTitle(trans('plugins/orders::orders.settings.title'));

        return OrdersSettingForm::create()->renderForm();
    }

    public function update(OrdersSettingRequest $request)
    {
        if ($request->has('google_ordersense_orders_delete_txt')) {
            File::delete(public_path('orders.txt'));

            return $this
                ->httpResponse()
                ->withUpdatedSuccessMessage();
        }

        if ($request->hasFile('orders_google_ordersense_txt_file')) {
            $request->file('orders_google_ordersense_txt_file')->move(public_path(), 'orders.txt');
        }

        return $this->performUpdate(
            Arr::except($request->validated(), ['orders_google_ordersense_txt_file'])
        );
    }
}
