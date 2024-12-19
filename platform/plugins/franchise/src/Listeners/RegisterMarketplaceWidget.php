<?php

namespace Botble\Franchise\Listeners;

use Botble\Base\Events\RenderingAdminWidgetEvent;
use Botble\Franchise\Widgets\SaleCommissionHtml;
use Botble\Franchise\Widgets\StoreRevenueTable;

class RegisterFranchiseWidget
{
    public function handle(RenderingAdminWidgetEvent $event): void
    {
        $event
            ->widget
            ->register([
                SaleCommissionHtml::class,
                StoreRevenueTable::class,
            ], FRANCHISE_MODULE_SCREEN_NAME);
    }
}
