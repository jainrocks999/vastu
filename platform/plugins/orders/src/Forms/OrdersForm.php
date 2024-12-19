<?php

namespace Botble\Orders\Forms;

use Botble\Orders\Facades\OrdersManager;
use Botble\Orders\Http\Requests\OrdersRequest;
use Botble\Orders\Models\Orders;
use Botble\Base\Facades\BaseHelper;
use Botble\Base\Forms\FieldOptions\DatePickerFieldOption;
use Botble\Base\Forms\FieldOptions\MediaImageFieldOption;
use Botble\Base\Forms\FieldOptions\NameFieldOption;
use Botble\Base\Forms\FieldOptions\SelectFieldOption;
use Botble\Base\Forms\FieldOptions\SortOrderFieldOption;
use Botble\Base\Forms\FieldOptions\StatusFieldOption;
use Botble\Base\Forms\FieldOptions\TextFieldOption;
use Botble\Base\Forms\Fields\DatePickerField;
use Botble\Base\Forms\Fields\MediaImageField;
use Botble\Base\Forms\Fields\NumberField;
use Botble\Base\Forms\Fields\OnOffField;
use Botble\Base\Forms\Fields\SelectField;
use Botble\Base\Forms\Fields\TextField;
use Botble\Base\Forms\FormAbstract;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OrdersForm extends FormAbstract
{
    public function setup(): void
    {
        $this
            ->model(Orders::class)
            ->setValidatorClass(OrdersRequest::class)
            ->add('name', TextField::class, NameFieldOption::make()->required())
            ->add('key', TextField::class, [
                'label' => trans('plugins/orders::orders.key'),
                'required' => true,
                'attr' => [
                    'placeholder' => trans('plugins/orders::orders.key'),
                    'data-counter' => 255,
                ],
                'default_value' => $this->generateOrdersKey(),
            ])
            ->add('order', NumberField::class, SortOrderFieldOption::make())
            ->add(
                'orders_type',
                SelectField::class,
                SelectFieldOption::make()
                    ->label(trans('plugins/orders::orders.orders_type'))
                    ->choices([
                        'custom_ad' => trans('plugins/orders::orders.custom_ad'),
                        'google_ordersense' => 'Google AdSense',
                    ])
            )
            ->addOpenCollapsible('orders_type', 'google_ordersense', $this->getModel()->orders_type)
            ->add(
                'google_ordersense_slot_id',
                TextField::class,
                TextFieldOption::make()
                    ->label(trans('plugins/orders::orders.google_ordersense_slot_id'))
                    ->placeholder('E.g: 1234567890')
            )
            ->addCloseCollapsible('orders_type', 'google_ordersense')
            ->addOpenCollapsible('orders_type', 'custom_ad', $this->getModel()->orders_type ?? 'custom_ad')
            ->add('url', TextField::class, [
                'label' => trans('plugins/orders::orders.url'),
                'attr' => [
                    'placeholder' => trans('plugins/orders::orders.url'),
                    'data-counter' => 255,
                ],
            ])
            ->add('open_in_new_tab', OnOffField::class, [
                'label' => trans('plugins/orders::orders.open_in_new_tab'),
                'default_value' => true,
            ])
            ->add('image', MediaImageField::class, MediaImageFieldOption::make())
            ->add('tablet_image', MediaImageField::class, [
                'label' => __('Tablet Image'),
                'help_block' => [
                    'text' => __('For devices with width from 768px to 1200px, if empty, will use the image from the desktop.'),
                ],
            ])
            ->add('mobile_image', MediaImageField::class, [
                'label' => __('Mobile Image'),
                'help_block' => [
                    'text' => __('For devices with width less than 768px, if empty, will use the image from the tablet.'),
                ],
            ])
            ->addCloseCollapsible('orders_type', 'custom_ad')
            ->add('status', SelectField::class, StatusFieldOption::make())
            ->when(($adLocations = OrdersManager::getLocations()) && count($adLocations) > 1, function () use ($adLocations) {
                $this->add(
                    'location',
                    SelectField::class,
                    SelectFieldOption::make()
                        ->label(trans('plugins/orders::orders.location'))
                        ->choices($adLocations)
                        ->searchable()
                        ->required()
                );
            })
            ->add(
                'expired_at',
                DatePickerField::class,
                DatePickerFieldOption::make()
                    ->label(trans('plugins/orders::orders.expired_at'))
                    ->defaultValue(BaseHelper::formatDate(Carbon::now()->addMonth()))
            )
            ->setBreakFieldPoint('status');
    }

    protected function generateOrdersKey(): string
    {
        do {
            $key = strtoupper(Str::random(12));
        } while (Orders::query()->where('key', $key)->exists());

        return $key;
    }
}
