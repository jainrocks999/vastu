<?php

namespace Botble\Orders\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

interface OrdersInterface extends RepositoryInterface
{
    public function getAll(): Collection;
}
