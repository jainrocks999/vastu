<?php

namespace Botble\Franchise\Enums;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Supports\Enum;
use Illuminate\Support\HtmlString;


class FranchiseStatusEnum extends Enum
{
    public const ACTIVATED = 'activated';

    public const LOCKED = 'locked';

    public static $langPath = 'plugins/franchise::franchise.status';

    public function toHtml(): HtmlString|string
    {
        $color = match ($this->value) {
            self::ACTIVATED => 'info',
            self::LOCKED => 'warning',
            default => 'primary',
        };

        return BaseHelper::renderBadge($this->label(), $color);
    }
}
