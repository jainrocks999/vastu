<?php

namespace Botble\Orders\Repositories\Eloquent;

use Botble\Orders\Repositories\Interfaces\OrdersInterface;
use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Illuminate\Database\Eloquent\Collection;

class OrdersRepository extends RepositoriesAbstract implements OrdersInterface
{
    public function getAll(): Collection
    {
        // @phpstan-ignore-next-line
        $data = $this->model
            ->wherePublished()
            ->notExpired()
            ->with(['metadata']);

        return $this->applyBeforeExecuteQuery($data)->get();
    }
}
