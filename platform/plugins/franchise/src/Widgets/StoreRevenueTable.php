<?php

namespace Botble\Franchise\Widgets;

use Botble\Base\Widgets\Table;
use Botble\Franchise\Tables\StoreRevenueTable as BaseStoreRevenueTable;

class StoreRevenueTable extends Table
{
    protected string $table = BaseStoreRevenueTable::class;

    protected string $route = 'franchise.reports.store-revenues';

    public function getLabel(): string
    {
        return trans('plugins/franchise::franchise.reports.store_revenues');
    }
}
