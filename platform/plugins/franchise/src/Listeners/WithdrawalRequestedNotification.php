<?php

namespace Botble\Franchise\Listeners;

use Botble\Base\Events\AdminNotificationEvent;
use Botble\Base\Supports\AdminNotificationItem;
use Botble\Franchise\Events\WithdrawalRequested;

class WithdrawalRequestedNotification
{
    public function handle(WithdrawalRequested $event): void
    {
        event(new AdminNotificationEvent(
            AdminNotificationItem::make()
                ->title(trans('plugins/franchise::withdrawal.new_withdrawal_request_notifications.new_withdrawal_request'))
                ->description(trans('plugins/franchise::withdrawal.new_withdrawal_request_notifications.description', [
                    'customer' => $event->customer->name,
                ]))
                ->action(trans('plugins/franchise::withdrawal.new_withdrawal_request_notifications.view'), route('franchise.withdrawal.edit', $event->withdrawal->id))
                ->permission('franchise.withdrawal.edit')
        ));
    }
}
