<?php

namespace Botble\Franchise\Http\Controllers\Fronts;

use Botble\Ecommerce\Http\Controllers\SpecificationGroupController as BaseSpecificationGroupController;
use Botble\Ecommerce\Models\Customer;
use Botble\Franchise\Forms\SpecificationGroupForm;
use Botble\Franchise\Tables\SpecificationGroupTable;

class SpecificationGroupController extends BaseSpecificationGroupController
{
    protected function getTable(): string
    {
        return SpecificationGroupTable::class;
    }

    protected function getForm(): string
    {
        return SpecificationGroupForm::class;
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
        return 'franchise.vendor.specification-groups.index';
    }

    protected function getEditRouteName(): string
    {
        return 'franchise.vendor.specification-groups.edit';
    }
}
