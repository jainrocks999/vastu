<?php

namespace Botble\Orders\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController as Controller;
use Botble\Base\Supports\Breadcrumb;

abstract class BaseController extends Controller
{
    protected function breadcrumb(): Breadcrumb
    {
        return parent::breadcrumb()
            ->add(trans('plugins/orders::orders.name'));
    }
}
