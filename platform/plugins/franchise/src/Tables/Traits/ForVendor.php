<?php

namespace Botble\Franchise\Tables\Traits;

use Botble\Franchise\Facades\FranchiseHelper;

trait ForVendor
{
    public function booted(): void
    {
        $this
            ->setView(FranchiseHelper::viewPath('vendor-dashboard.table.base'))
            ->bulkChangeUrl(route('franchise.vendor.table.bulk-change.save'))
            ->bulkChangeDataUrl(route('franchise.vendor.table.bulk-change.data'))
            ->bulkActionDispatchUrl(route('franchise.vendor.table.bulk-action.dispatch'))
            ->filterInputUrl(route('franchise.vendor.table.filter.input'));
    }
}
