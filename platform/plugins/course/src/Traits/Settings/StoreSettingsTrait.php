<?php

namespace Botble\Course\Traits\Settings;

use Botble\Course\Facades\CourseHelper;
use Botble\Setting\Facades\Setting;

trait StoreSettingsTrait
{
    protected function saveSettings(array $settings): void
    {
        if (! $settings) {
            return;
        }

        $prefix = CourseHelper::getSettingPrefix();

        foreach ($settings as $key => $value) {
            Setting::set(
                $prefix . $key,
                is_array($value) ? json_encode(array_values(array_filter($value))) : $value
            );
        }

        Setting::save();
    }
}
