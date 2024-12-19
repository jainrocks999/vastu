<?php

namespace Botble\Franchise\Http\Controllers\Fronts;

use Botble\Ecommerce\Http\Controllers\SpecificationAttributeController as BaseSpecificationAttributeController;
use Botble\Ecommerce\Models\Customer;
use Botble\Franchise\Forms\SpecificationAttributeForm;
use Botble\Franchise\Tables\SpecificationAttributeTable;

class SpecificationAttributeController extends BaseSpecificationAttributeController
{
    protected function getTable(): string
    {
        return SpecificationAttributeTable::class;
    }

    protected function getForm(): string
    {
        return SpecificationAttributeForm::class;
    }

    protected function getAdditionalDataForSaving(): array
    {
        return [
            'author_type' => Customer::class,
            'author_id' => auth('customer')->id(),
        ];
    }

    protected function getIndexRouteName(): string
    {
        return 'franchise.vendor.specification-attributes.index';
    }

    protected function getEditRouteName(): string
    {
        return 'franchise.vendor.specification-attributes.edit';
    }
}
