<?php

namespace Botble\Franchise\Http\Controllers\Settings;

use Botble\Franchise\Facades\FranchiseHelper;
use Botble\Setting\Http\Controllers\SettingController as BaseSettingController;

abstract class SettingController extends BaseSettingController
{
    protected function saveSettings(array $data, string $prefix = ''): void
    {
        parent::saveSettings($data, FranchiseHelper::getSettingKey());
    }
}
