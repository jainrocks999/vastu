<?php

namespace Botble\Orders\Forms\Settings;

use Botble\Orders\Http\Requests\Settings\OrdersSettingRequest;
use Botble\Base\Facades\Html;
use Botble\Base\Forms\FieldOptions\CodeEditorFieldOption;
use Botble\Base\Forms\FieldOptions\HtmlFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\CodeEditorField;
use Botble\Base\Forms\Fields\HtmlField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Setting\Forms\SettingForm;

class OrdersSettingForm extends SettingForm
{
    public function setup(): void
    {
        parent::setup();

        $this
            ->setSectionTitle(trans('plugins/orders::orders.settings.title'))
            ->setSectionDescription(trans('plugins/orders::orders.settings.description'))
            ->setValidatorClass(OrdersSettingRequest::class);

        $googleAdSenseLink = Html::link('https://www.google.com/ordersense', 'Google AdSense', ['target' => '_blank']);

        $this
            ->add(
                'orders_google_ordersense_auto_orders',
                CodeEditorField::class,
                CodeEditorFieldOption::make()
                    ->label(trans('plugins/orders::orders.settings.google_ordersense_auto_orders_snippet'))
                    ->helperText(trans('plugins/orders::orders.settings.google_ordersense_auto_orders_snippet_helper', [
                        'link' => $googleAdSenseLink,
                    ]))
                    ->value(setting('orders_google_ordersense_auto_orders'))
            )
            ->add(
                'orders_google_ordersense_unit_client_id',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/orders::orders.settings.google_ordersense_unit_orders_client_id'))
                    ->helperText(trans('plugins/orders::orders.settings.google_ordersense_unit_orders_client_id_helper', [
                        'link' => $googleAdSenseLink,
                    ]))
                    ->value(setting('orders_google_ordersense_unit_client_id'))
                    ->placeholder('ca-pub-123456789')
            )
            ->add(
                'orders_google_ordersense_what_is_client_id',
                HtmlField::class,
                HtmlFieldOption::make()
                    ->view('plugins/orders::partials.google-ordersense.client-id')
            )
            ->add('orders_google_ordersense_txt_file', 'file', [
                'label' => __('Your Google Ordersense orders.txt'),
            ])
            ->add(
                'orders_google_ordersense_txt',
                HtmlField::class,
                HtmlFieldOption::make()->view('plugins/orders::partials.google-ordersense.txt')
            )
        ;
    }
}
