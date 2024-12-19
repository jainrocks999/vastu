<?php

namespace Botble\Franchise\Events;

use Botble\Base\Events\Event;
use Botble\Ecommerce\Models\Customer;
use Botble\Franchise\Models\Withdrawal;
use Illuminate\Queue\SerializesModels;

class WithdrawalRequested extends Event
{
    use SerializesModels;

    public function __construct(public Customer $customer, public Withdrawal $withdrawal)
    {
    }
}
