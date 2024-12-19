<?php

namespace Botble\Franchise\Providers;

use Botble\Base\Events\RenderingAdminWidgetEvent;
use Botble\Ecommerce\Events\OrderCreated;
use Botble\Franchise\Events\WithdrawalRequested;
use Botble\Franchise\Listeners\OrderCreatedEmailNotification;
use Botble\Franchise\Listeners\RegisterFranchiseWidget;
use Botble\Franchise\Listeners\RenderingSiteMapListener;
use Botble\Franchise\Listeners\SaveVendorInformationListener;
use Botble\Franchise\Listeners\SendMailAfterVendorRegistered;
use Botble\Franchise\Listeners\WithdrawalRequestedNotification;
use Botble\Theme\Events\RenderingSiteMapEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SaveVendorInformationListener::class,
            SendMailAfterVendorRegistered::class,
        ],
        RenderingSiteMapEvent::class => [
            RenderingSiteMapListener::class,
        ],
        OrderCreated::class => [
            OrderCreatedEmailNotification::class,
        ],
        WithdrawalRequested::class => [
            WithdrawalRequestedNotification::class,
        ],
        RenderingAdminWidgetEvent::class => [
            RegisterFranchiseWidget::class,
        ],
    ];
}
