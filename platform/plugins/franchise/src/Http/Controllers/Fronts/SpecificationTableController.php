<?php

namespace Botble\Franchise\Http\Controllers\Fronts;

use Botble\Ecommerce\Http\Controllers\SpecificationTableController as BaseSpecificationTableController;
use Botble\Ecommerce\Models\Customer;
use Botble\Franchise\Forms\SpecificationTableForm;
use Botble\Franchise\Tables\SpecificationTableTable;

class SpecificationTableController extends BaseSpecificationTableController
{
    protected function getTable(): string
    {
        return SpecificationTableTable::class;
    }

    protected function getForm(): string
    {
        return SpecificationTableForm::class;
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
        return 'franchise.vendor.specification-tables.index';
    }

    protected function getEditRouteName(): string
    {
        return 'franchise.vendor.specification-tables.edit';
    }
}
